<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\RegisterPharmacyRequest;

class RegisterPharmacyDTO
{
    public function __construct(
        public readonly string $userName,
        public readonly string $email,
        public readonly string $password,
        public readonly string $pharmacyName,
        public readonly string $licenseNumber,
        public readonly string $phone,
        public readonly ?string $address,
    ) {}

    public static function fromRequest(RegisterPharmacyRequest $request): self
    {
        return new self(
            userName: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            pharmacyName: $request->validated('pharmacy_name'),
            licenseNumber: $request->validated('license_number'),
            phone: $request->validated('phone'),
            address: $request->validated('address'),
        );
    }
}
