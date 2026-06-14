<?php

declare(strict_types=1);

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

final class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_number'        => ['nullable', 'string', 'unique:invoices,invoice_number'],
            'type'                  => ['required', 'string', 'in:cash,credit,draft'],
            'customer_id'           => ['nullable', 'integer'],
            'notes'                 => ['nullable', 'string'],
            'currency'              => ['nullable', 'string', 'max:3'],
            'is_confirmed'          => ['nullable', 'boolean'],
            'items'                 => ['required', 'array', 'min:1'],
            'items.*.product_id'    => ['nullable', 'integer', 'exists:products,id'],
            'items.*.product_name'  => ['required', 'string'],
            'items.*.quantity'      => ['required', 'integer', 'min:1'],
            'items.*.unit_price'    => ['required', 'numeric', 'min:0'],
            'items.*.discount'      => ['nullable', 'numeric', 'min:0'],
            'items.*.tax_percent'   => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
