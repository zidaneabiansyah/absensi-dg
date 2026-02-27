@extends('layouts.app')

@section('title', 'RFID Simulator')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">RFID Simulator</h1>
        <p class="text-gray-600">Gunakan halaman ini untuk mensimulasikan scan RFID tanpa alat IoT.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Pilih Siswa untuk Simulasi Scan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($students as $student)
            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
                <div>
                    <h4 class="font-bold text-gray-900">{{ $student->name }}</h4>
                    <p class="text-xs text-gray-500">UID: <span class="font-mono text-blue-600">{{ $student->rfid_uid }}</span></p>
                </div>
                <button 
                    onclick="simulateScan('{{ $student->rfid_uid }}')"
                    class="bg-[#ff8a01] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#e67a00] transition-transform active:scale-95"
                >
                    Tap Kartu
                </button>
            </div>
            @empty
            <div class="col-span-2 p-8 text-center text-gray-500">
                Belum ada siswa yang didaftarkan RFID-nya.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Output Log -->
    <div id="log-container" class="bg-gray-900 text-green-400 p-6 rounded-2xl font-mono text-sm h-64 overflow-y-auto hidden">
        <div id="log-content"></div>
    </div>
</div>

<script>
async function simulateScan(uid) {
    const logContainer = document.getElementById('log-container');
    const logContent = document.getElementById('log-content');
    
    logContainer.classList.remove('hidden');
    
    const timestamp = new Date().toLocaleTimeString();
    addLog(`[${timestamp}] Mengirim scan: UID ${uid}...`);

    try {
        const response = await fetch('/api/v1/attendance/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ rfid_uid: uid })
        });

        const result = await response.json();
        
        if (result.success) {
            addLog(`[${timestamp}] SUCCESS: ${result.message}`, 'text-green-400');
        } else {
            addLog(`[${timestamp}] ERROR: ${result.message}`, 'text-red-400');
        }
    } catch (error) {
        addLog(`[${timestamp}] CONNECTION ERROR: ${error.message}`, 'text-red-400');
    }
    
    logContainer.scrollTop = logContainer.scrollHeight;
}

function addLog(message, colorClass = '') {
    const logContent = document.getElementById('log-content');
    const div = document.createElement('div');
    div.className = `mb-1 ${colorClass}`;
    div.textContent = message;
    logContent.appendChild(div);
}
</script>
@endsection
