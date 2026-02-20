<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .summary {
            margin: 20px 0;
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .summary-item .label {
            font-size: 10px;
            color: #666;
        }
        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-H { background-color: #d4edda; color: #155724; }
        .status-T { background-color: #d1ecf1; color: #0c5460; }
        .status-I { background-color: #fff3cd; color: #856404; }
        .status-S { background-color: #f8d7da; color: #721c24; }
        .status-A { background-color: #f8d7da; color: #721c24; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN ABSENSI SISWA</h1>
        <p>Sistem Absensi Sekolah</p>
        @if($type === 'daily')
            <p>Tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
        @elseif($type === 'monthly')
            <p>Periode: {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
        @endif
    </div>

    @if($reportData)
        @if(isset($reportData['summary']))
        <div class="summary">
            <div class="summary-item">
                <div class="label">Total</div>
                <div class="value">{{ $reportData['summary']['total'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Hadir</div>
                <div class="value" style="color: #28a745;">{{ $reportData['summary']['hadir'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Terlambat</div>
                <div class="value" style="color: #17a2b8;">{{ $reportData['summary']['terlambat'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Izin</div>
                <div class="value" style="color: #ffc107;">{{ $reportData['summary']['izin'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Sakit</div>
                <div class="value" style="color: #fd7e14;">{{ $reportData['summary']['sakit'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Alpha</div>
                <div class="value" style="color: #dc3545;">{{ $reportData['summary']['alpha'] }}</div>
            </div>
        </div>
        @endif

        @if($type === 'daily' || $type === 'monthly')
            @if(isset($reportData['attendances']) && $reportData['attendances']->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="10%">NIS</th>
                        <th width="25%">Nama Siswa</th>
                        <th width="15%">Kelas</th>
                        <th width="10%">Status</th>
                        <th width="10%">Jam Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['attendances'] as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attendance->date->format('d/m/Y') }}</td>
                        <td>{{ $attendance->student->nis }}</td>
                        <td>{{ $attendance->student->name }}</td>
                        <td>{{ $attendance->student->class->name }}</td>
                        <td>
                            <span class="status-badge status-{{ $attendance->status }}">
                                {{ $attendance->status_label }}
                            </span>
                        </td>
                        <td>{{ $attendance->time_in ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

        @elseif($type === 'class')
            @if(isset($reportData['class']))
            <h3>Kelas: {{ $reportData['class']->name }}</h3>
            <p>Periode: {{ \Carbon\Carbon::parse($reportData['period']['start'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($reportData['period']['end'])->format('d M Y') }}</p>
            
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">NIS</th>
                        <th width="30%">Nama Siswa</th>
                        <th width="8%">Hadir</th>
                        <th width="8%">Terlambat</th>
                        <th width="8%">Izin</th>
                        <th width="8%">Sakit</th>
                        <th width="8%">Alpha</th>
                        <th width="8%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['students'] as $index => $studentReport)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $studentReport['student']->nis }}</td>
                        <td>{{ $studentReport['student']->name }}</td>
                        <td style="text-align: center;">{{ $studentReport['hadir'] }}</td>
                        <td style="text-align: center;">{{ $studentReport['terlambat'] }}</td>
                        <td style="text-align: center;">{{ $studentReport['izin'] }}</td>
                        <td style="text-align: center;">{{ $studentReport['sakit'] }}</td>
                        <td style="text-align: center;">{{ $studentReport['alpha'] }}</td>
                        <td style="text-align: center;">{{ $studentReport['total'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

        @elseif($type === 'student')
            @if(isset($reportData['student']))
            <h3>{{ $reportData['student']->name }}</h3>
            <p>NIS: {{ $reportData['student']->nis }} | Kelas: {{ $reportData['student']->class->name }}</p>
            <p>Periode: {{ \Carbon\Carbon::parse($reportData['period']['start'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($reportData['period']['end'])->format('d M Y') }}</p>
            
            @if($reportData['attendances']->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Status</th>
                        <th width="15%">Jam Masuk</th>
                        <th width="15%">Jam Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['attendances'] as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attendance->date->format('d/m/Y') }}</td>
                        <td>
                            <span class="status-badge status-{{ $attendance->status }}">
                                {{ $attendance->status_label }}
                            </span>
                        </td>
                        <td>{{ $attendance->time_in ?? '-' }}</td>
                        <td>{{ $attendance->time_out ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            @endif
        @endif
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>

</body>
</html>
