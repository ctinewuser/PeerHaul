<?php
use Illuminate\Support\Facades\Mail;

/**
 * Send mail
 */
function sendMails($template, $data = array()) {
    Mail::send($template, ['data' => $data], function ($message) use ($data) {
        $message->from(env('FROM_EMAIL'), config('app.name'));
        $message->to($data['email'])->subject($data['subject']);
    });
    if (Mail::failures()) {
        return false;
    } else {
        return true;
    }
}
