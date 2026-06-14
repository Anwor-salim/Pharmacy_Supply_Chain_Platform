<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class LoginAction
{
    public function __invoke(LoginDTO $dto): array
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['البيانات المدخلة غير صحيحة.'],
            ]);
        }

        if ($dto->accountType === 'pharmacy' && $user->userable_type !== \App\Models\Pharmacy::class) {
            throw ValidationException::withMessages([
                'email' => ['هذا الحساب ليس حساب صيدلية، يرجى التسجيل أو الدخول بحساب الشركة.'],
            ]);
        }

        if ($dto->accountType === 'company' && $user->userable_type !== \App\Models\Company::class) {
            throw ValidationException::withMessages([
                'email' => ['هذا الحساب ليس حساب شركة، يرجى التسجيل أو الدخول كصيدلية.'],
            ]);
        }


        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user->load('userable'),
            'token' => $token,
        ];
    }
}
