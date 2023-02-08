<?php

namespace App\Http\Controllers;

use App\Models\LineBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __invoke(Request $request, LineBot $lineBot)
    {
        // LINE request signature validation
        $hash = hash_hmac('sha256', $request->getContent(), $lineBot->channel_secret, true);
        $signature = base64_encode($hash);

        if ($request->header('x-line-signature') !== $signature) {
            abort(404);
        }

        if (! $request->has('events')) { // this should never have happened
            Log::warning('LINE bad response');
            abort(400);
        }

        if (empty($request->input('events'))) { // LINE verify webhook
            return ['ok' => true];
        }

        $lineBot->messages()->create([
            'payload' => $request->all(),
        ]);

        return ['ok' => true];
    }
}
