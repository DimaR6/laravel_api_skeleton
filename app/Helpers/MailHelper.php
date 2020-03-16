<?php


namespace App\Helpers;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailHelper
{

    public static function sendMail($to, $template, $subject = '', $params = [], $attachData = [])
    {
        $params['title'] = $params['title'] ?? '';
        $params['bcc_to_admin'] = $params['bcc_to_admin'] ?? false;

        $locale = App::getLocale();
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        try {
            Mail::send(
                'mails.index',
                [
                    'params' => $params,
                    'locale' => $locale,
                    'template' => $template
                ],
                function ($mail) use ($fromAddress, $fromName, $to, $params, $subject, $attachData) {
                    /** @var $mail Message */
                    $mail->subject($subject);

                    $bcc_emails = [];
                    if($params['bcc_to_admin']){
                        $bcc_emails = config('mail.bcc');
                    }

                    if(isset($params['bcc']) && count($params['bcc']) > 0){
                        $bcc_emails = array_merge($bcc_emails, $params['bcc']);
                    }

                    if(count($bcc_emails) > 0){
                        $mail->bcc(array_unique($bcc_emails));
                    }

                    foreach ($attachData as $file){
                        if($file['data']){
                            $mail->attachData($file['data'], $file['name'], ['mime' => $file['mime']]);
                        }
                    }

                    $mail->from($fromAddress, $fromName);
                    $mail->to($to);
                }
            );
        } catch (\Exception $exception) {
            Log::critical(__('messages.mail-send-fail').' '.$exception->getMessage());
        }
    }
}