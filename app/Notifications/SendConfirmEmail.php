<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendConfirmEmail extends Notification
{
    use Queueable;

    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $magic_link = config('app.magic_link');
        $id = $notifiable->getKey();
        $url = "$magic_link/$id/$notifiable->confirmation_code";
        return (new MailMessage)
            ->subject('Email verification')
            ->line('Email verification')
            ->line('To validate your email click on the button below.')
            ->action('Email verification', $url)
            ->line('Thank you for using our application!');
    }

}
