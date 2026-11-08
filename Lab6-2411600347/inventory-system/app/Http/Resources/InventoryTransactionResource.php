<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => $this->product->name ?? 'Deleted product',
            'product_url' => route('products.show', $this->product_id),
            'type' => $this->type_label,
            'type_color' => $this->type_color,
            'quantity' => $this->quantity,
            'reference_number' => $this->reference_number,
            'user' => $this->user->name ?? 'System',
            'created_at' => $this->created_at->format('M d, h:i A'),
        ];
    }
}