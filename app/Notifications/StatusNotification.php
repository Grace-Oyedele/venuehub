<?php

namespace App\Notifications;

use App\Models\Venue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusNotification extends Notification
{
    use Queueable;

    /**
     * @var Venue
     */
    private $venue;
    /**
     * @var string
     */
    private $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Venue $venue, string $status)
    {
        $this->venue = $venue;
        $this->status = $status;
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
        $venue = $this->venue;
        $status = $this->status;
        if ($status == Venue::APPROVED) {
            $subject = "Venue Approved";
            $statusText = "approved";
        }else {
            $subject = "Venue Disaabled";
            $statusText = "disapproved";
        }
        $mailMessage =  (new MailMessage)
                    ->subject($subject)
                    ->line("Dear user {$venue->user->first_name},  your venue: {$venue->name} was $statusText")
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
        return $mailMessage;
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
