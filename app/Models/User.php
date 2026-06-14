<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Company;
use App\Models\Pharmacy;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'userable_id', 'userable_type'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, \Laravel\Sanctum\HasApiTokens;

    /**
     * Get the parent userable model (Company or Pharmacy).
     */
    public function userable()
    {
        return $this->morphTo();
    }

    /**
     * Get the company associated with the user.
     */
    public function getCompanyAttribute()
    {
        return $this->userable_type === Company::class ? $this->userable : null;
    }

    /**
     * Get the pharmacy associated with the user.
     */
    public function getPharmacyAttribute()
    {
        return $this->userable_type === Pharmacy::class ? $this->userable : null;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
