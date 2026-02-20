<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:H,I,S,A,T',
            'attendances.*.time_in' => 'nullable|date_format:H:i',
            'attendances.*.time_out' => 'nullable|date_format:H:i',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal wajib diisi',
            'attendances.required' => 'Data absensi wajib diisi',
            'attendances.*.student_id.required' => 'ID siswa wajib diisi',
            'attendances.*.student_id.exists' => 'Siswa tidak ditemukan',
            'attendances.*.status.required' => 'Status absensi wajib diisi',
            'attendances.*.status.in' => 'Status absensi tidak valid',
        ];
    }
}
