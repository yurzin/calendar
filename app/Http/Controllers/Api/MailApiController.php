<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMailApiRequest;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class MailApiController extends Controller
{
    public function confirmation(StoreMailApiRequest $request)
    {
        $data = $request->validated();

        // Тестовый запрос Tilda при подключении вебхука приходит без email
        if (empty($data['email'])) {
            return response('ok', 200);
        }

        $data['email'] = mb_strtolower($data['email']);

        try {
            $user = User::firstOrNew(['email' => $data['email']]);

            if ($user->email_verified_at) {
                return response('ok', 200);
            }

            $user->fill($data);
            $user->token = $user->token ?: Str::random(64);
            $user->save();
        } catch (QueryException $e) {
            // 1062 — дубль по уникальному email (параллельная двойная отправка)
            if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
                return response('ok', 200);
            }
            throw $e;
        }

        // Кулдаун: не чаще одного письма в 5 минут на адрес
        if (!Cache::add('verification-mail:' . $user->id, 1, now()->addMinutes(5))) {
            return response('ok', 200);
        }

        try {
            Mail::to($user->email)->send(new VerificationMail($user->token));
        } catch (\Throwable $e) {
            report($e);
        }

        return response('ok', 200);
    }

    public function verifyAccount(string $token)
    {
        $user = User::where('token', $token)->first();

        if (!$user) {
            $status = 'invalid';
        } elseif ($user->email_verified_at) {
            $status = 'already';
        } else {
            $user->forceFill(['email_verified_at' => now()])->save();
            $status = 'verified';
        }

        return redirect()->away(config('app.landing_url') . '?email_status=' . $status);
    }
}
