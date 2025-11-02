<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Payment;

class PaymentVerifiedNotification extends Notification
{
    use Queueable;

    protected $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
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
                    ->subject('Payment Verified - ' . $this->payment->listing->title)
                    ->line('Your payment of ₱' . number_format($this->payment->amount, 2) . ' for ' . $this->payment->listing->title . ' has been verified and marked as completed.')
                    ->line('Payment Method: ' . ucfirst(str_replace('_', ' ', $this->payment->payment_method)))
                    ->line('Payment Date: ' . $this->payment->payment_date->format('M d, Y'))
                    ->line('Thank you for your payment!')
                    ->action('View Property', url('/tenant/my-rental'))
                    ->line('If you have any questions, please contact your landlord.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'listing_id' => $this->payment->listing_id,
            'amount' => $this->payment->amount,
            'status' => 'verified',
            'message' => 'Your payment has been verified by the landlord.',
        ];
    }
}
