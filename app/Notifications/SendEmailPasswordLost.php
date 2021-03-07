<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SendEmailPasswordLost extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    public $delayExpirationToken;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token, int $delayExpirationToken)
    {
        $this->token = $token;
        $this->delayExpirationToken = $delayExpirationToken;
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
        $url = config('params.app_url_front') . '/password-reset?token=' . $this->token;

        $message = new MailMessage();
        $message->subject('Mot de passe perdu.');
        $message->greeting('Bonjour ' . $notifiable->first_name . ' ' . $notifiable->last_name . ',');
        $message->line('Pour ré initialiser votre mot de passe :');
        $message->action('Je re initialise mon mot de passe', $url)->level('success');
        $message->line('Ce lien est actif pour une durée de ' . $this->delayExpirationToken . ' minutes.');

        return $message;
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
        ];
    }
}
