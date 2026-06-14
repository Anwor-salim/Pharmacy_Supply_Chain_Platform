<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Pharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'license_number',
        'address',
        'status',
    ];

    /**
     * Get the pharmacy's user.
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}
