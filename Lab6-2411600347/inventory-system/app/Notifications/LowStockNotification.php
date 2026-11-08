<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// ShouldQueue makes this run in the background through the queue
class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Product $product)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Stock Alert: ' . $this->product->name)
            ->line($this->product->name . ' (' . $this->product->sku . ') is running low.')
            ->line('Current quantity: ' . $this->product->quantity . ' (reorder level: ' . $this->product->reorder_level . ')')
            ->line('Suggested action: reorder from ' . ($this->product->supplier ?? 'your supplier') . '.')
            ->action('View Product', route('products.show', $this->product->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'sku' => $this->product->sku,
            'quantity' => $this->product->quantity,
            'reorder_level' => $this->product->reorder_level,
            'message' => 'Only ' . $this->product->quantity . ' left (reorder level ' . $this->product->reorder_level . '). Please reorder.',
        ];
    }
}