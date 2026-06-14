<?php

declare(strict_types=1);

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'            => ['nullable', 'string', 'max:255'],
            'scientific_name'=> ['nullable', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'strength'        => ['nullable', 'string', 'max:255'],
            'dosage_form'     => ['nullable', 'string', 'max:255'],
            'production_date' => ['nullable', 'date'],
            'expiry_date'     => ['nullable', 'date', 'after:production_date'],
            'batch_number'    => ['nullable', 'string', 'max:255'],
            'price'           => ['nullable', 'numeric', 'min:0'],
            'stock_quantity'  => ['nullable', 'integer', 'min:0'],
            'status'          => ['nullable', 'boolean'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
