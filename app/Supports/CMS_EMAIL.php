<?php

namespace App\Supports;


use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Mail;

class CMS_EMAIL {

    /**
     * @param $view
     * @param $to
     * @param array $data
     * @param array $cc
     * @param array $bcc
     * @param string $subject
     */
    static function send($view, $to, $data = [], $cc = [], $bcc = [], $subject = null)
    {
        $data['logo'] = env('APP_LOGO');
        if(empty($subject)) {
            $subject = config('app.name');
        }
        Mail::send($view, $data, function ($message) use ($to, $subject, $data, $cc, $bcc) {
            $message->to($to);

            if (!empty($cc)) {
                $message->cc($cc);
            }
            $message->bcc($bcc);

            $message->subject($subject);
        });
    }

    /**
     * @param $otp
     * @param $to
     * @param array $cc
     * @param array $bcc
     * @return void
     */
    static function sendQueue($template, $to, $bcc = [], $cc = [])
    {
        SendEmail::dispatch($template, $to,$bcc = [], $cc = []);
    }
}
