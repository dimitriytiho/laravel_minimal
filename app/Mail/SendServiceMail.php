<?php


namespace App\Mail;

use App\Helpers\Func;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendServiceMail extends Notification
{
    use Queueable;

    public $title;
    public $body;
    public $values;
    public $template;
    public $h1;
    private $layout;

    /**
     * Create a new notification instance.
     *
     * @return void
     *
     *
     * Переменные для отправки письма
     * $title - Заголовок письма.
     * $body - Содержимое письма, можно просто текст или вёрстку. Если используется $template дополнительный вид, то этот параметр не используется, передайте null, необязательный параметр.
     * $values - Данные для использования в видах, необязательный параметр.
     * $template - Название вида для оптравки письма из папки views/mail (к примеру user), необязательный параметр.
     * $h1 - Если нужно H1 передать из вида $template, то передайте null, тогда заголовок $title используйте в виде, который передаёте в $template, необязательный параметр.
     *
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
        $values = $this->values;
        $h1 = $this->h1;
        $body = $this->body;
        $view = null;
        $site_name = Func::site('name') ?? ' ';
        $color = config('add.primary', '#ccc');

        if ($this->template && view()->exists("mail.{$this->template}")) {
            $view = view("mail.{$this->template}",
                compact('title', 'values', 'body', 'color', 'site_name'))
                ->render();
        }

        $email = Func::site('email');
        $tel = Func::site('tel');
        $tel = $tel ? __('s.or_call') . $tel : null;

        return (new MailMessage)
            ->view("layouts.{$this->layout}",
                compact('view', 'title', 'values', 'h1', 'body', 'site_name', 'color', 'email', 'tel'))
            ->subject(__('s.Information_letter'));



        /*return (new MailMessage)
            ->greeting('Hello!')
            ->line('One of your invoices has been paid!')
            ->action('View Invoice', url('/invoice/'.$this->invoice->id))
            ->line('Thank you for using our application!');*/
    }
}
