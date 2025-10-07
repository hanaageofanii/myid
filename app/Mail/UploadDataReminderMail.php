<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UploadDataReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        $tanggal = now()->locale('id')->translatedFormat('l, d F Y');

        return $this->subject('Selamat Pagi ☀️ - Jangan Lupa Upload Data Hari Ini')
                    ->view('emails.reminder_upload', [
                        'logo' => function ($message) {
                            return $message->embed(public_path('image/logo.png'));
                        },
                        'tanggal' => $tanggal,
                    ]);
    }
}