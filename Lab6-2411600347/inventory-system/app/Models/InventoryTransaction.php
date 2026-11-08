<?php

namespace App\Models;

use App\Observers\InventoryTransactionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([InventoryTransactionObserver::class])]
class InventoryTransaction extends Model
{
    use HasFactory;

    public const TYPES = [
        'stock_in' => 'Stock In',
        'stock_out' => 'Stock Out',
        'adjustment' => 'Adjustment',
    ];

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'reference_number',
        'reason',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // query scopes
    public function scopeStockIn($query)
    {
        return $query->where('type', 'stock_in');
    }

    public function scopeStockOut($query)
    {
        return $query->where('type', 'stock_out');
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at')->orderByDesc('id');
    }

    // helpers for the views
    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getTypeColorAttribute()
    {
        return match ($this->type) {
            'stock_in' => 'success',
            'stock_out' => 'danger',
            default => 'secondary',
        };
    }
}