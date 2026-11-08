<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'category' => $this->category,
            'quantity' => $this->quantity,
            'reorder_level' => $this->reorder_level,
            'status' => $this->stock_status,
            'status_color' => $this->stock_status_color,
            'url' => route('products.show', $this->id),
        ];
    }
}