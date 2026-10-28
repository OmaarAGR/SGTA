<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Appointment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['nullable', 'exists:clients,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'technician_id' => ['nullable'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
        ];
    }
}
