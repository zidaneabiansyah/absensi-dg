<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:H,I,S,A,T',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status absensi wajib diisi',
            'status.in' => 'Status absensi tidak valid',
        ];
    }
}
