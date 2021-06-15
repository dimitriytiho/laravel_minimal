<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/*
 * Пример отправки письма:
 *
 * $user = \App\Models\User::first(); // Объект пользователя.
 * $mail = app()
        ->make(\Illuminate\Notifications\Messages\MailMessage::class)
        ->subject('Subject mail')
        ->greeting('Hello! This title!')
        ->line('Text for body...')
        //->action('Button text', url('/'))
        //->attach('/path/to/file')
        ->line('Text for footer...'); // Настройки письма.
 * $send = app()->make(\App\Mail\SendDefault::class, ['mailMessage' => $mail]); // Создаём объект этого класса.
 *
 * \Illuminate\Support\Facades\Notification::send($user, $send); // Оправляем письмо.
 */
class SendDefault extends Notification
{
    use Queueable;

    private $mailMessage;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MailMessage $mailMessage)
    {
        $this->mailMessage = $mailMessage;
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
        return $this->mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
