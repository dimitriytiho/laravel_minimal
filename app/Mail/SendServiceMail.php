<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendServiceMail extends Notification
{
    use Queueable;

    private $title;
    private $body;
    private $subjectMail;

    /**
     * Create a new notification instance.
     *
     * @return void
     *
     *
     * @param string $title - Заголовок письма, передайте null, чтобы не использовать заголовок и футер, необязательный параметр.
     * @param string $body - Содержимое письма, необязательный параметр.
     * @param string $subject - Тема письма, по-умолчанию: Информационное письмо, необязательный параметр.
     */
    public function __construct($title = null, $body = null, $subject = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->subjectMail = $subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $title = $this->title;
        $body = $this->body;
        $subject = $this->subjectMail ?: __('s.Information_letter');
        return (app()->make(MailMessage::class))
            ->view('layouts.mail',
                compact('title', 'body', 'subject'))
            ->subject($subject);
    }
}
