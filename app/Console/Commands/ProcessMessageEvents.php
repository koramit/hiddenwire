<?php

namespace App\Console\Commands;

use App\Models\Attachment;
use App\Models\LineBot;
use App\Models\LineGroup;
use App\Models\LineMessage;
use App\Models\SimplifiedEvent;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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
    public function handle()
    {
        $messages = LineMessage::query()
            ->with('bot:id,channel_access_token')
            ->where('processed', false)
            ->get();

        if ($messages->isEmpty()) {
            return Command::SUCCESS;
        }

        $messages->each(fn($message) => $this->process($message));

        return Command::SUCCESS;
    }

    protected function process(LineMessage $message)
    {
        foreach ($message->payload['events'] as $event) {
            if ($event['type'] !== 'message') {
                continue;
            }
            $this->handleMessage($event, $message);
        }

        $message->processed = true;
        $message->save();
    }

    protected function handleMessage(array &$event, LineMessage $message)
    {
        $group = null;
        if ($event['source']['type'] === 'group') {
            $group = LineGroup::query()
                ->firstOrCreate(
                    ['line_group_id' => $event['source']['groupId']],
                );
        }

        $profile = Http::withToken($message->bot->channel_access_token)
            ->get("https://api.line.me/v2/bot/profile/{$event['source']['userId']}")
            ->json();

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

        $common = [
            'user_id' => $user->id,
            'line_group_id' => $group?->id,
            'line_message_id' => $message->id,
        ];

        if ($event['message']['type'] === 'text') {
            SimplifiedEvent::query()
                ->create($common + [
                    'type' => 'text',
                    'message' => $event['message']['text'],
                ]);
        } elseif (in_array($event['message']['type'], ['image', 'video', 'file'])) {
            $attachment = $this->putContent($event, $message->bot->channel_access_token);
            SimplifiedEvent::query()
                ->create($common + [
                        'type' => $event['message']['type'],
                        'attachment_id' => $attachment->id,
                    ]);
        }
    }

    protected function putContent(array &$event, string $token)
    {
        $response = Http::withToken($token)
            ->get("https://api-data.line.me/v2/bot/message/{$event['message']['id']}/content");

        if ($event['message']['type'] === 'file') {
            $filename = $event['message']['fileName'];
        } else {
            $filename = $event['message']['id'] . '.' . [
                    'image/jpeg' => 'jpg',
                    'video/mp4' => 'mp4',
                ][$response->headers()['Content-type']];
        }

        $path = Storage::put('line/content/' . $filename, $response->body());

        return Attachment::query()
            ->create([
                'path' => $path,
                'filename' => $filename,
            ]);
    }
}
