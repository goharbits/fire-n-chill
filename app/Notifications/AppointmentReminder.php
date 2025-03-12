<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AppointmentReminder extends Notification
{
    use Queueable;

    public $appointment;
    public $reminderType;

    /**
     * Create a new notification instance.
     */
   public function __construct($appointment, $reminderType)
    {
        $this->appointment = $appointment;
        $this->reminderType = $reminderType;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['mail'];
         return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return  (new MailMessage)
            ->subject('Appointment Reminder')
            ->line("Your appointment is coming up in {$this->reminderType}.")
            ->action('View Appointment', url('/appointments/' . $this->appointment->id))
            ->line('Thank you for using our application!');

    }


      public function toArray($notifiable)
    {
       return [
            'appointment_id' => $this->appointment->id,
            'reminder_type' => $this->reminderType,
            'start_date_time' => $this->appointment->start_date_time,
        ];
    }

     public function toBroadcast($notifiable)
    {
       return  new BroadcastMessage([
            'appointment_id' => $this->appointment->id,
            'reminder_type' => $this->reminderType,
            'start_date_time' => $this->appointment->start_date_time,
        ]);

    }
}