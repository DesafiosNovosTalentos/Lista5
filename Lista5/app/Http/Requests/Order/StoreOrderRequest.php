<?php

namespace App\Http\Requests\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'product_name' => strip_tags($this->product_name ?? ''),
        ]);
    }
}
