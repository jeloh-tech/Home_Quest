<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Payment;

class PaymentReceiptNotification extends Notification
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
        $listing = $this->payment->listing;
        $landlord = $listing->user;

        $message = (new MailMessage)
                    ->subject('Rent Payment Receipt')
                    ->line("Your rent payment of ₱" . number_format($this->payment->amount, 2) . " has been successfully processed.")
                    ->line("Property: {$listing->title}")
                    ->line("Location: {$listing->location}")
                    ->line("Landlord: {$landlord->name}")
                    ->line("Payment Method: " . ucfirst(str_replace('_', ' ', $this->payment->payment_method)))
                    ->line("Payment Date: " . $this->payment->payment_date->format('M d, Y H:i'))
                    ->line("Transaction ID: {$this->payment->id}")
                    ->line("On Time: " . ($this->payment->is_on_time ? 'Yes' : 'No'));

        if ($this->payment->notes) {
            $message->line("Notes: {$this->payment->notes}");
        }

        if ($this->payment->receipt_url) {
            $message->line("Receipt: " . asset('storage/' . $this->payment->receipt_url));
        }

        $message->action('View Payment History', url('/tenant/payment-history'))
                ->line('Thank you for your payment!');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $listing = $this->payment->listing;
        $landlord = $listing->user;

        return [
            'payment_id' => $this->payment->id,
            'property_title' => $listing->title,
            'landlord_name' => $landlord->name,
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
