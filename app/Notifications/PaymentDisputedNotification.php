<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentDisputedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Disputed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your payment has been disputed by an administrator.')
            ->line('Payment Details:')
            ->line('Amount: $' . number_format($this->payment->amount, 2))
            ->line('Property: ' . ($this->payment->listing ? $this->payment->listing->title : 'N/A'))
            ->line('Dispute Reason: ' . ($this->payment->dispute_reason ?? 'N/A'))
            ->line('Disputed At: ' . $this->payment->disputed_at->format('M d, Y H:i'))
            ->action('View Payment Details', url('/tenant/pay-rent'))
            ->line('If you have any questions, please contact support.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payment Disputed',
            'message' => 'Your payment of $' . number_format($this->payment->amount, 2) . ' has been disputed.',
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'property_title' => $this->payment->listing ? $this->payment->listing->title : 'N/A',
            'dispute_reason' => $this->payment->dispute_reason,
            'disputed_at' => $this->payment->disputed_at,
            'type' => 'payment_disputed',
        ];
    }
}
