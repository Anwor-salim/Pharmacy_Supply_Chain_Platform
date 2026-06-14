<?php

namespace App\Http\Requests\Company;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'scientific_name' => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'strength'        => ['nullable', 'string', 'max:255'],
            'dosage_form'     => ['nullable', 'string', 'max:255'],
            'production_date' => ['required', 'date'],
            'expiry_date'     => ['required', 'date', 'after:production_date'],
            'batch_number'    => ['nullable', 'string', 'max:255'],
            'price'           => ['required', 'numeric', 'min:0'],
            'stock_quantity'  => ['required', 'integer', 'min:0'],
            'status'          => ['nullable', 'boolean'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
