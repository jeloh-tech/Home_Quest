<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Payment;

class PaymentReceivedNotification extends Notification
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
        $tenant = $this->payment->tenant;
        $listing = $this->payment->listing;
        $landlord = $listing->user;

        $message = (new MailMessage)
                    ->subject('New Rent Payment Received')
                    ->line("A new rent payment of ₱" . number_format($this->payment->amount, 2) . " has been received from {$tenant->name}.")
                    ->line("Property: {$listing->title}")
                    ->line("Location: {$listing->location}")
                    ->line("Payment Method: " . ucfirst(str_replace('_', ' ', $this->payment->payment_method)))
                    ->line("Payment Date: " . $this->payment->payment_date->format('M d, Y H:i'))
                    ->line("On Time: " . ($this->payment->is_on_time ? 'Yes' : 'No'));

        if ($this->payment->notes) {
            $message->line("Notes: {$this->payment->notes}");
        }

        if ($this->payment->receipt_url) {
            $message->line("Receipt: " . asset('storage/' . $this->payment->receipt_url));
        }

        $message->action('View Payment Details', url('/landlord/payment-history'))
                ->line('Please verify the payment and update its status accordingly.');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $tenant = $this->payment->tenant;
        $listing = $this->payment->listing;

        return [
            'payment_id' => $this->payment->id,
            'tenant_name' => $tenant->name,
            'property_title' => $listing->title,
            'amount' => $this->payment->amount,
            'payment_method' => $this->payment->payment_method,
            'status' => $this->payment->status,
            'payment_date' => $this->payment->payment_date->format('Y-m-d H:i:s'),
            'is_on_time' => $this->payment->is_on_time,
            'notes' => $this->payment->notes,
            'receipt_url' => $this->payment->receipt_url ? asset('storage/' . $this->payment->receipt_url) : null,
        ];
    }
}
