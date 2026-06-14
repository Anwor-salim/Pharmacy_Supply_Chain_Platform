<?php

declare(strict_types=1);

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

final class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount'           => ['required', 'numeric', 'min:0.01'],
            'method'           => ['required', 'string', 'in:cash,bank,card'],
            'reference_number' => ['nullable', 'string'],
            'paid_at'          => ['nullable', 'date'],
        ];
    }
}
