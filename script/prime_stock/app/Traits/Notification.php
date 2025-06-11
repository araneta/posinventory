<?php

namespace App\Traits;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

trait Notification
{
    /**
     * Send Mail Notification to User
     */
    public function sendMailNotification($email, $subject, $message): void
    {
        $data = [
            'subject' => $subject,
            'message' => $message
        ];
        Mail::to($email)->send(new SendMail($data));
    }
}
