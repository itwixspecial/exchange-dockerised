<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateExchangeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'currency_from_id' => 'required|exists:currencies,id',
            'currency_to_id' => 'required|exists:currencies,id|different:currency_from_id',
            'amount_from' => 'required|numeric|min:0',
            'amount_to' => 'required|numeric|min:0',
        ];
    }
}
