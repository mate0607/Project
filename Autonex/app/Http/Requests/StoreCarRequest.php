<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'make_model' => ['required', 'string', 'max:255'],
            'vin' => ['nullable', 'string', 'max:255'],
            'license_plate' => ['nullable', 'string', 'max:20'],
            'year' => ['nullable', 'integer', 'between:1900,' . date('Y')],
        ];
    }
}
