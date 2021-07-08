<?php

namespace App\Mail;

use App\Support\Func;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    private $layout;
    private $viewPath;
    public $title;
    public $body;
    public $values;
    public $template;
    public $h1;

    /**
     * Create a new message instance.
     *
     * @return void
     *
     * Переменные для отправки письма
     * @param string $title - Заголовок письма.
     * @param string $body - Содержимое письма, можно просто текст или вёрстку. Если используется $template дополнительный вид, то этот параметр не используется, передайте null, необязательный параметр.
     * @param mixed $values - Данные для использования в видах, необязательный параметр.
     * @param string $template - Название вида для отправки письма из папки views/mail (к примеру user), необязательный параметр.
     * @param string $h1 - Если нужно H1 передать из вида $template, то передайте null, тогда заголовок $title используйте в виде, который передаёте в $template, необязательный параметр.

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
    public function __construct($title, $body = null, $values = null, $template = null, $h1 = true)
    {
        $this->layout = 'mail';
        $this->title = $title;
        $this->body = $body;
        $this->values = $values;
        $this->template = $template;
        $this->h1 = $h1;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = $this->title;
        $values = $this->values;
        $h1 = $this->h1;
        $body = $this->body;
        $view = null;
        $site_name = Func::site('name') ?: ' ';
        $color = config('add.primary', '#ccc');


        if ($this->template && view()->exists("mail.{$this->template}")) {
            $view = view("mail.{$this->template}", compact('title', 'values', 'body', 'color', 'site_name'))->render();
        }

        // Если передаём вид $template, то $body используем только в этом $template, а в основном шаблоне не используем
        if ($view) {
            $body = null;
        }

        $email = Func::site('email');
        $tel = Func::site('tel');
        $tel = $tel ? __('s.or_call') . $tel : null;


        return $this->view("layouts.{$this->layout}",
            compact('view', 'title', 'values', 'h1', 'body', 'site_name', 'color', 'email', 'tel'))
            ->subject(__('s.Information_letter'));
    }
}
