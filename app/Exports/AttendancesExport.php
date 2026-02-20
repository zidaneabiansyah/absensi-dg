<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendancesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $attendances;
    protected $title;

    public function __construct($attendances, $title = 'Laporan Absensi')
    {
        $this->attendances = $attendances;
        $this->title = $title;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Status',
            'Jam Masuk',
            'Jam Keluar',
            'Sumber',
        ];
    }

    public function map($attendance): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $attendance->date->format('d/m/Y'),
            $attendance->student->nis,
            $attendance->student->name,
            $attendance->student->class->name,
            $attendance->status_label,
            $attendance->time_in ?? '-',
            $attendance->time_out ?? '-',
            ucfirst($attendance->source),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return $this->title;
    }
}
