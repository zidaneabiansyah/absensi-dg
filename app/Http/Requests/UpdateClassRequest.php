<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'homeroom_teacher' => 'nullable|string|max:255',
            'academic_year' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kelas wajib diisi',
            'name.max' => 'Nama kelas maksimal 255 karakter',
            'academic_year.required' => 'Tahun ajaran wajib diisi',
            'academic_year.max' => 'Tahun ajaran maksimal 20 karakter',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus active atau inactive',
        ];
    }
}
