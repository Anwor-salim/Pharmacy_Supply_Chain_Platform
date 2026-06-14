<?php

declare(strict_types=1);

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

final class StoreReturnInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_number'      => ['nullable', 'string', 'unique:invoices,invoice_number'],
            'original_invoice_id' => ['required', 'integer', 'exists:invoices,id'],
            'total_amount'        => ['required', 'numeric'],
        ];
    }
}
