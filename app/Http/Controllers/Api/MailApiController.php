<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMailApiRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MailApiController extends Controller
{
    public function confirmation(StoreMailApiRequest $request)
    {
        if (!empty($request->email)) {
            $token = Str::random(64);
            $data = $request->except(['tranid', 'formid', 'consent-personal-data']);
            $new_data = array_merge($data, ['token' => $token]);
            User::create($new_data);
            Mail::send('mails.verification', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Электронная почта для подтверждения');
            });
            return response('Success', 200);
        } else {
            return response('Request is empty', 200);
        }
    }

    public function verifyAccount($token)
    {
        $verifyUser = User::where('token', $token)->first();
        $message = 'К сожалению, ваш адрес электронной почты не может быть идентифицирован';
        if (!is_null($verifyUser)) {
            if (!$verifyUser->email_verified_at) {
                $verifyUser->email_verified_at = Carbon::now();
                $verifyUser->save();
                $message = "Your e-mail is verified";
            } else {
                $message = "Your e-mail is already verified";
            }
        }
        return redirect('http://xn--80acbojebux9agf4k.xn--p1ai');
    }
}
