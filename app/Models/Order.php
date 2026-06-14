<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Order extends Model
{
    protected $fillable = [
        'company_id',
        'pharmacy_id',
        'order_number',
        'status',
        'total_amount',
        'notes',
        'delivered_at',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'delivered_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'shipped'    => 'تم الشحن',
            'delivered'  => 'تم التسليم',
            'cancelled'  => 'ملغي',
            default      => $this->status,
        };
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Order $order) {
            $order->order_number = 'ORD-' . now()->format('Y') . str_pad((string) (self::count() + 1), 5, '0', STR_PAD_LEFT);
        });
    }
}
