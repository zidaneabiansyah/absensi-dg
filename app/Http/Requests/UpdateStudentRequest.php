<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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
        $studentId = $this->route('student');

        return [
            'nis' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'nis')->ignore($studentId),
            ],
            'nisn' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'rfid_uid' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('students', 'rfid_uid')->ignore($studentId),
            ],
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nis.required' => 'NIS wajib diisi',
            'nis.unique' => 'NIS sudah terdaftar',
            'nis.max' => 'NIS maksimal 50 karakter',
            'name.required' => 'Nama siswa wajib diisi',
            'name.max' => 'Nama siswa maksimal 255 karakter',
            'class_id.required' => 'Kelas wajib dipilih',
            'class_id.exists' => 'Kelas tidak ditemukan',
            'rfid_uid.unique' => 'RFID UID sudah terdaftar',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus active atau inactive',
        ];
    }
}
