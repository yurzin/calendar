<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public string $token)
    {
    }

    public function build()
    {
        return $this->subject('Подтвердите адрес электронной почты')
            ->view('mails.verification', ['token' => $this->token]);
    }
}
