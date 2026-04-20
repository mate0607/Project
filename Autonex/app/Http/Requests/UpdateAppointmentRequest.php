<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
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
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'description' => ['nullable', 'string', 'max:1000'],
            'service' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:pending,confirmed,in_progress,completed,cancelled'],
            'service_stage' => ['nullable', 'in:received,inspected,in_progress,ready'],
            'mechanic_name' => ['nullable', 'string', 'max:255'],
            'total_cost' => ['nullable', 'numeric', 'min:0'],
            'service_report' => ['nullable', 'string', 'max:5000'],
            'issues_found' => ['nullable', 'string', 'max:5000'],
            'critical_warning' => ['nullable', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'photo_title' => ['nullable', 'string', 'max:255'],
            'photos' => ['nullable', 'array', 'max:10'],
            'photos.*' => ['image', 'max:5120'],
        ];
    }
}
