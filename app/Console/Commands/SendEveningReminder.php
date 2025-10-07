<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\EveningReminderMail;

class SendEveningReminder extends Command
{
    protected $signature = 'reminder:evening';
    protected $description = 'Kirim email pengingat upload data sore ke banyak alamat';

    public function handle()
    {
        $emails = [
            'suryaniernalis440@gmail.com',
            'izanurul4@gmail.com',
            'pipitsaflendra11@gmail.com',
            'atiyastuti2@gmail.com',
            'ekaningsih122@gmail.com',
            'malindamariaf5@gmail.com',
            'alizapkb@gmail.com',
            'kprgcvtkr@gmail.com',
            'kartikasariningsih94@gmail.com',
            'srinuroktavia201000@gmail.com',
            'hesty3404@gmail.com',
            'triastutihutami.99@gmail.com',
            'hernagcv@gmail.com',
            'zakiagcv@gmail.com',
            'hanaapkb@gmail.com',
            'gcvsystem@gmail.com',
            '',
            '',

        ];

        foreach ($emails as $email) {
            Mail::to($email)->send(new EveningReminderMail());
        }

        $this->info('Reminder sore berhasil dikirim ke semua alamat.');
    }
}