<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterPharmacyDTO;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class RegisterPharmacyAction
{
    public function __invoke(RegisterPharmacyDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $pharmacy = Pharmacy::create([
                'name' => $dto->pharmacyName,
                'license_number' => $dto->licenseNumber,
                'phone' => $dto->phone,
                'address' => $dto->address,
            ]);

            $user = $pharmacy->user()->create([
                'name' => $dto->userName,
                'email' => $dto->email,
                'password' => $dto->password,
                'role' => 'pharmacy',
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user->load('userable'),
                'token' => $token,
            ];
        });
    }
}
