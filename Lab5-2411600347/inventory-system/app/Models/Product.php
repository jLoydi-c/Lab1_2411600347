<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'description', 'category',
        'quantity', 'reorder_level', 'unit_price', 'supplier',
    ];

    public function isLowStock()
    {
        return $this->quantity <= $this->reorder_level;
    }

    public function isOutOfStock()
    {
        return $this->quantity == 0;
    }
}