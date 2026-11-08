<?php

namespace App\Observers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;

class InventoryTransactionObserver
{
    // runs BEFORE the transaction is saved
    // this is where we block negative stock
    public function creating(InventoryTransaction $transaction): void
    {
        // lock the product row so two requests can't change it at the same time
        $product = Product::lockForUpdate()->findOrFail($transaction->product_id);

        $newQuantity = $product->quantity + $transaction->quantity;

        if ($newQuantity < 0) {
            throw new \Exception(
                'Not enough stock for ' . $product->name . '. Available: ' . $product->quantity . '.'
            );
        }

        // save the running balance on the transaction row
        $transaction->balance_after = $newQuantity;
    }

    // runs AFTER the transaction is saved
    // this is where we update the product and send alerts
    public function created(InventoryTransaction $transaction): void
    {
        $product = $transaction->product;
        $oldQuantity = $transaction->balance_after - $transaction->quantity;

        $product->quantity = $transaction->balance_after;
        $product->save();

        // only alert when the product just dropped into low stock or just ran out
        // this stops duplicate alerts for products that were already low
        $justBecameLow = $product->quantity <= $product->reorder_level
            && $oldQuantity > $product->reorder_level;
        $justRanOut = $product->quantity <= 0 && $oldQuantity > 0;

        if ($justBecameLow || $justRanOut) {
            $admins = User::where('is_admin', true)->get();
            Notification::send($admins, new LowStockNotification($product));
        }
    }
}