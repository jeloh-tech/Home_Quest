<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationRejected extends Notification
{
    use Queueable;

    protected $user;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $reason)
    {
        $this->user = $user;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Verification Has Been Rejected')
            ->greeting('Hello ' . $this->user->name . ',')
            ->line('We regret to inform you that your landlord verification has been rejected.')
            ->line('Reason: ' . $this->reason)
            ->line('You can submit new verification documents by visiting your dashboard.')
            ->action('Submit New Documents', url('/landlord/verify'))
            ->line('If you have any questions, please contact our support team.')
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your verification has been rejected.',
            'reason' => $this->reason,
        ];
    }
}
