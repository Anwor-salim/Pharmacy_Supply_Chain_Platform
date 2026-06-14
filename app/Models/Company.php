<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

final class Company extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'commercial_register',
        'address',
        'status',
    ];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
