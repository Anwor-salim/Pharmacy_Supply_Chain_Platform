<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterCompanyDTO;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class RegisterCompanyAction
{
    public function __invoke(RegisterCompanyDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $company = Company::create([
                'name' => $dto->companyName,
                'commercial_register' => $dto->commercialRegister,
                'phone' => $dto->phone,
                'address' => $dto->address,
            ]);

            $user = $company->user()->create([
                'name' => $dto->userName,
                'email' => $dto->email,
                'password' => $dto->password,
                'role' => 'company',
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user->load('userable'),
                'token' => $token,
            ];
        });
    }
}
