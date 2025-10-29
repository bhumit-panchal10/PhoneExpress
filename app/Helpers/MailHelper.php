<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailHelper
{
    /**
     * Send Partner With Us email
     *
     * @param string $name
     * @param string $email
     * @param string $mobile
     * @param string $messageContent
     * @param string|null $subject
     * @return bool
     */
    public static function sendPartnerMail($data)
    {
        // Fetch mail config from DB

        $sendEmailDetails = DB::table('sendemaildetails')->where('id', 9)->first();

        if (!$sendEmailDetails) {
            Log::error('Mail config not found for ID 9');
            return false;
        }

        // Prepare message meta
        $msg = [
            'FromMail' => $sendEmailDetails->strFromMail,
            'Title' => $sendEmailDetails->strTitle,
            'ToEmail' => $data['Email'],
            'Subject' => $sendEmailDetails->strSubject ?? $data['Email'],
        ];

        // Prepare data for template
        $data = [
            'Name' => $data['Name'],
            'Email' => $data['Email'],
            'Mobile' => $data['Mobile'],
            'Message' => $data['Message'],
            'Subject' => $data['Subject'],
        ];

        try {
            Mail::send('emails.inquirymail', ['data' => $data], function ($message) use ($msg) {
                $message->from($msg['FromMail'], $msg['Title']);
                $message->to($msg['ToEmail'])->subject($msg['Subject']);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Mail sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
