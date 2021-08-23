<?php

namespace App\Mail;

use App\Support\Func;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    private $title;
    private $body;
    private $subjectMail;

    /**
     * Create a new message instance.
     *
     * @return void
     *
     * @param string $title - Заголовок письма, передайте null, чтобы не использовать заголовок и футер, необязательный параметр.
     * @param string $body - Содержимое письма, необязательный параметр.
     * @param string $subject - Тема письма, по-умолчанию: Информационное письмо, необязательный параметр.

    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.yandex.ru
    MAIL_PORT=587 // Возможно 25, 465
    MAIL_USERNAME=mail@yandex.ru
    MAIL_PASSWORD=password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="${MAIL_USERNAME}"
    MAIL_FROM_NAME="${APP_NAME}"
     *
     */
    public function __construct($title = null, $body = null, $subject = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->subjectMail = $subject;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = $this->title;
        $body = $this->body;
        $subject = $this->subjectMail ?: __('s.Information_letter');
        return $this->view('layouts.mail',
            compact('title', 'body', 'subject'))
            ->subject($subject);
    }


    /**
     *
     * @return void
     * Отправить письмо.
     *
     * @param string|array $emails - передать email получателей, если несколько можно через запятую или массивом.
     * @param string $title - Заголовок письма, передайте null, чтобы не показывать, необязательный параметр.
     * @param string $body - Содержимое письма, необязательный параметр.
     * @param string $subject - Тема письма, по-умолчанию: Информационное письмо, необязательный параметр.
     */
    public static function get($emails, $title, $body = null, $subject = null)
    {
        if ($emails && $title) {
            try {

                if (is_string($emails)) {
                    $emails = str_replace(' ', '', $emails);
                    $emails = explode(',', $emails);
                }

                if (is_array($emails)) {
                    Mail::to($emails)->send(new self($title, $body, $subject));
                }

            } catch (\Exception $e) {
                Func::getError("Error sending mail: {$e}", __METHOD__, false);
            }
        }
    }
}
