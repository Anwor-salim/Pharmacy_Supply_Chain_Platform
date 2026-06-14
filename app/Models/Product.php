<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Product extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'scientific_name',
        'description',
        'strength',
        'dosage_form',
        'production_date',
        'expiry_date',
        'batch_number',
        'price',
        'stock_quantity',
        'status',
        'image_path',
    ];

    protected $casts = [
        'production_date' => 'date',
        'expiry_date'     => 'date',
        'price'           => 'float',
        'stock_quantity'  => 'integer',
        'status'          => 'boolean',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
