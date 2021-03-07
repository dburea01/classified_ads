<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SendEmailValidateUser extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

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
        // dd($notifiable);
        $url = config('params.app_url_front') . '/validate-user-registration?token=' . $notifiable->email_verification_code;
        $message = new MailMessage();
        $message->subject('Validation de votre inscription.');
        $message->greeting('Bonjour ' . $notifiable->first_name . ' ' . $notifiable->last_name . ',');
        $message->line('Merci pour votre inscription sur ' . config('app.name'));
        $message->action('Je valide mon inscription', $url)->level('success');

        // $message->action('A bientôt sur '.config('app.name'), config('params.app_url_front'))->level('success');

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
