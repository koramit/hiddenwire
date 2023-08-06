<?php

namespace App\Console\Commands;

use App\Models\Attachment;
use App\Models\LineGroup;
use App\Models\LineMessage;
use App\Models\SimplifiedEvent;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessMessageEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:message-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process message events';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $messages = LineMessage::query()
            ->with('bot:id,channel_access_token')
            ->where('processed', false)
            ->get();

        if ($messages->isEmpty()) {
            return static::SUCCESS;
        }

        $messages->each(fn ($message) => $this->process($message));

        return static::SUCCESS;
    }

    protected function process(LineMessage $message): int
    {
        foreach ($message->payload['events'] as $event) {
            if ($event['type'] !== 'message') {
                continue;
            }
            if (! $this->handleMessage($event, $message)) {
                return static::FAILURE;
            }
        }

        $message->processed = true;
        $message->save();

        return static::SUCCESS;
    }

    protected function handleMessage(array &$event, LineMessage $message): bool
    {
        if (SimplifiedEvent::query()->find($event['message']['id'])) {
            return true;
        }

        $group = null;
        if ($event['source']['type'] === 'group') {
            if (! $group = $this->getLineGroup($event['source']['groupId'], $message->bot->channel_access_token)) {
                return false;
            }
        }

        if (! $user = User::query()->where('line_user_id', $event['source']['userId'])->first()) {
            try {
                $profile = Http::retry(3, 100)
                    ->timeout(4)
                    ->withToken($message->bot->channel_access_token)
                    ->get("https://api.line.me/v2/bot/profile/{$event['source']['userId']}")
                    ->json();
            } catch (Exception $e) {
                if ($e->getCode() !== 404) {
                    Log::error('LINEAPI@getProfile '.$e->getMessage());

                    return false;
                }

                $profile = [
                    'displayName' => 'not a friend',
                    'pictureUrl' => null,
                ];
            }

            $user = User::query()
                ->firstOrCreate(
                    ['line_user_id' => $event['source']['userId']],
                    [
                        'name' => $profile['displayName'],
                        'avatar_url' => $profile['pictureUrl'],
                        'status' => $profile['statusMessage'] ?? null,
                        'password' => Hash::make($event['source']['userId']),
                    ]
                );
        }

        $common = [
            'id' => $event['message']['id'],
            'user_id' => $user->id,
            'line_group_id' => $group?->id,
            'line_message_id' => $message->id,
            'timestamp' => $event['timestamp'],
        ];

        if ($event['message']['type'] === 'text') {
            SimplifiedEvent::query()
                ->create($common + [
                    'type' => 'text',
                    'message' => $event['message']['text'],
                ]);

            return true;
        }

        if (! in_array($event['message']['type'], ['image', 'video', 'file', 'audio'])) {
            return true;
        }

        if (! $attachment = $this->putContent($event, $message->bot->channel_access_token)) {
            return false;
        }

        SimplifiedEvent::query()
            ->create($common + [
                'type' => $event['message']['type'],
                'attachment_id' => $attachment->id ?? null,
            ]);

        return true;
    }

    protected function putContent(array $event, string $token): ?Attachment
    {
        try {
            $response = Http::retry(3, 100)
                ->withToken($token)
                ->get("https://api-data.line.me/v2/bot/message/{$event['message']['id']}/content");
        } catch (Exception $e) {
            if ($e->getCode() === 400) {
                return new Attachment();
            }

            Log::error('LINEAPI@getContent '.$e->getMessage());

            return null;
        }

        if ($event['message']['type'] === 'file') {
            $filename = $event['message']['fileName'];
        } else {
            $filename = $event['message']['id'].'.'.[
                'image/jpeg' => 'jpg',
                'video/mp4' => 'mp4',
                'audio/x-m4a' => 'm4a',
            ][$response->headers()['Content-Type'][0]];
        }

        $path = 'l/c/'.$filename;

        try {
            Storage::put($path, $response->body());
        } catch (Exception $e) {
            Log::error("https://api-data.line.me/v2/bot/message/{$event['message']['id']}/content");
        }
        return Attachment::query()
            ->create([
                'path' => $path,
                'filename' => $filename,
            ]);
    }

    protected function getLineGroup(string $lineGroupId, string $token): ?LineGroup
    {
        /** @var LineGroup $lineGroup */
        $lineGroup = LineGroup::query()
            ->firstOrNew(
                ['line_group_id' => $lineGroupId],
            );

        if ($lineGroup->exists) {
            return $lineGroup;
        }

        // call LINE api to get group info
        try {
            $response = Http::timeout(4)
                ->retry(3, 100)
                ->withToken($token)
                ->get("https://api.line.me/v2/bot/group/$lineGroupId/summary");
        } catch (Exception $e) {
            Log::error('LINEAPI@getGroup '.$e->getMessage());

            return null;
        }
        $lineGroup->name = $response->json()['groupName'] ?? null;
        $lineGroup->save();

        return $lineGroup;
    }
}
