<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\RegisterCompanyRequest;

class RegisterCompanyDTO
{
    public function __construct(
        public readonly string $userName,
        public readonly string $email,
        public readonly string $password,
        public readonly string $companyName,
        public readonly string $commercialRegister,
        public readonly string $phone,
        public readonly ?string $address,
    ) {}

    public static function fromRequest(RegisterCompanyRequest $request): self
    {
        return new self(
            userName: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            companyName: $request->validated('company_name'),
            commercialRegister: $request->validated('commercial_register'),
            phone: $request->validated('phone'),
            address: $request->validated('address'),
        );
    }
}
