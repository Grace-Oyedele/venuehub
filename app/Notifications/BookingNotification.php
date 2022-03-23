<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Venue;
use App\Models\VenueBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingNotification extends Notification
{
    use Queueable;

    /**
     * @var VenueBooking
     */
    private $booking;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(VenueBooking $booking)
    {
        $this->booking = $booking;
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
        $user = $this->booking->user;
        $venue = $this->booking->venue;
        return (new MailMessage)
                    ->subject("Venue Booking Notification")
                    ->line("Dear {$user->first_name}, your booking for {$venue->name} of {$venue->price} was successful")
                    ->line("Date: {$this->booking->date}")
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
