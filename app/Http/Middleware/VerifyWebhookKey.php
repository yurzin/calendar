<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = trim((string) config('services.mail_webhook.key'));

        $given = $request->header('X-Webhook-Key')
            ?? $request->input('X-Webhook-Key')
            ?? $request->input('key');

        $given = is_string($given) ? trim($given) : null;

        if ($expected === '' || $given === null || !hash_equals($expected, $given)) {
            Log::warning('webhook key rejected', [
                'expected_set'  => $expected !== '',
                'expected_len'  => strlen($expected),
                'given_present' => $given !== null,
                'given_len'     => $given === null ? null : strlen($given),
                'header_names'  => array_keys($request->headers->all()),
                'field_names'   => array_keys($request->all()),
            ]);

            abort(403);
        }

        return $next($request);
    }
}
