<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
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
            'car_id' => ['nullable', 'integer', 'exists:cars,id'],
            'vehicle_type' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'body_type' => ['nullable', 'string', 'max:255'],
            'engine_cc' => ['nullable', 'integer', 'min:0'],
            'fuel_type' => ['nullable', 'string', 'max:255'],
            'documents_available' => ['nullable', 'boolean'],
            'document_type' => ['nullable', 'string', 'max:255'],
            'technical_inspection' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'car_condition' => ['required', 'string', 'max:255'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }
}
