@extends('layouts.app')

@section('title', 'Registrasi Kartu RFID')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Registrasi Kartu RFID</h1>
    <p class="text-gray-600">Scan kartu RFID dan assign ke siswa</p>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Left: Scan Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">1. Scan Kartu RFID</h2>
        
        <div class="text-center py-8">
            <div id="scan-status" class="mb-4">
                <div class="inline-block p-4 bg-gray-100 rounded-full mb-4">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
                <p class="text-gray-600">Klik tombol di bawah untuk mengaktifkan mode scan</p>
            </div>

            <button 
                id="activate-btn" 
                onclick="activateRegistrationMode()"
                class="bg-[#ff8a01] text-white px-6 py-3 rounded-lg hover:bg-[#e67a00] transition-colors font-semibold"
            >
                🔓 Aktifkan Mode Scan Kartu
            </button>

            <div id="countdown" class="mt-4 text-sm text-gray-500 hidden">
                Mode aktif: <span id="countdown-timer" class="font-bold">60</span> detik
            </div>
        </div>

        <!-- Scanned UID Display -->
        <div id="scanned-uid-box" class="hidden mt-6 p-4 bg-green-50 border-2 border-green-500 rounded-lg">
            <p class="text-sm text-gray-600 mb-2">Kartu terdeteksi:</p>
            <p id="scanned-uid" class="text-2xl font-mono font-bold text-green-700"></p>
            <p class="text-xs text-gray-500 mt-2">Scan pada: <span id="scanned-time"></span></p>
        </div>
    </div>

    <!-- Right: Assign to Student -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">2. Assign ke Siswa</h2>

        <form method="POST" action="{{ route('rfid.assign') }}" id="assign-form">
            @csrf

            <input type="hidden" name="rfid_uid" id="rfid_uid_input" value="">

            <div class="mb-6">
                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Siswa <span class="text-red-500">*</span>
                </label>
                <select 
                    id="student_id" 
                    name="student_id" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
                >
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $student)
                    <option value="{{ $student->id }}">
                        {{ $student->name }} - {{ $student->class->name }} (NIS: {{ $student->nis }})
                    </option>
                    @endforeach
                </select>
                @error('student_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    UID Kartu RFID
                </label>
                <input 
                    type="text" 
                    id="rfid_uid_display"
                    readonly
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 font-mono"
                    placeholder="Belum ada kartu yang di-scan"
                >
            </div>

            <button 
                type="submit" 
                id="submit-btn"
                disabled
                class="w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed"
            >
                Daftarkan Kartu
            </button>
        </form>

        @if($students->isEmpty())
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                ℹ️ Semua siswa sudah memiliki kartu RFID atau tidak ada siswa aktif.
            </p>
        </div>
        @endif
    </div>

</div>

<script>
let pollingInterval = null;
let countdownInterval = null;
let countdownSeconds = 0;

// Activate registration mode
async function activateRegistrationMode() {
    try {
        const response = await fetch('/api/v1/rfid/mode/activate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById('activate-btn').disabled = true;
            document.getElementById('activate-btn').classList.add('bg-gray-300', 'cursor-not-allowed');
            document.getElementById('activate-btn').classList.remove('bg-[#ff8a01]', 'hover:bg-[#e67a00]');
            document.getElementById('activate-btn').textContent = '⏳ Mode Scan Aktif...';

            document.getElementById('scan-status').innerHTML = `
                <div class="inline-block p-4 bg-green-100 rounded-full mb-4 animate-pulse">
                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-green-600 font-semibold">Mode Scan Aktif - Silakan tap kartu RFID</p>
            `;

            // Show countdown
            countdownSeconds = data.timeout;
            document.getElementById('countdown').classList.remove('hidden');
            startCountdown();

            // Start polling
            startPolling();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal mengaktifkan mode scan');
    }
}

// Start countdown timer
function startCountdown() {
    document.getElementById('countdown-timer').textContent = countdownSeconds;
    
    countdownInterval = setInterval(() => {
        countdownSeconds--;
        document.getElementById('countdown-timer').textContent = countdownSeconds;

        if (countdownSeconds <= 0) {
            clearInterval(countdownInterval);
            resetUI();
        }
    }, 1000);
}

// Start polling for scanned UID
function startPolling() {
    pollingInterval = setInterval(async () => {
        try {
            const response = await fetch('/api/v1/rfid/last-scanned');
            const data = await response.json();

            if (data.success && data.uid) {
                // UID found!
                displayScannedUID(data.uid, data.scanned_at);
                stopPolling();
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }, 1000); // Poll every 1 second
}

// Stop polling
function stopPolling() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }
}

// Display scanned UID
function displayScannedUID(uid, scannedAt) {
    document.getElementById('scanned-uid').textContent = uid;
    document.getElementById('scanned-time').textContent = scannedAt;
    document.getElementById('scanned-uid-box').classList.remove('hidden');

    document.getElementById('rfid_uid_input').value = uid;
    document.getElementById('rfid_uid_display').value = uid;

    document.getElementById('submit-btn').disabled = false;
    document.getElementById('submit-btn').classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
    document.getElementById('submit-btn').classList.add('bg-[#ff8a01]', 'text-white', 'hover:bg-[#e67a00]');

    document.getElementById('countdown').classList.add('hidden');
}

// Reset UI
function resetUI() {
    document.getElementById('activate-btn').disabled = false;
    document.getElementById('activate-btn').classList.remove('bg-gray-300', 'cursor-not-allowed');
    document.getElementById('activate-btn').classList.add('bg-[#ff8a01]', 'hover:bg-[#e67a00]');
    document.getElementById('activate-btn').textContent = '🔓 Aktifkan Mode Scan Kartu';

    document.getElementById('scan-status').innerHTML = `
        <div class="inline-block p-4 bg-gray-100 rounded-full mb-4">
            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
            </svg>
        </div>
        <p class="text-gray-600">Klik tombol di bawah untuk mengaktifkan mode scan</p>
    `;

    document.getElementById('countdown').classList.add('hidden');
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    stopPolling();
});
</script>

@endsection
