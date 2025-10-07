<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class EveningReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        $tanggal = now()->locale('id')->translatedFormat('l, d F Y');
        return $this->subject('Selamat Sore 🌇 - Sudah Upload Data Hari Ini?')
                    ->view('emails.reminder_evening', [
                        'logo' => function ($message) {
                            return $message->embed(public_path('image/logo.png'));
                        },
                        'tanggal' => $tanggal,
                    ]);
    }
}