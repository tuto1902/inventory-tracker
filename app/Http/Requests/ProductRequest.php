<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'sku' => ['required'],
            'description' => ['nullable'],
            'price' => ['required', 'numeric'],
            'stock_quantity' => ['required', 'integer'],
            'supplier_id' => ['required', 'exists:suppliers'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
