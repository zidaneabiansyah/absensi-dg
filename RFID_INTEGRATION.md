# 🔌 RFID Integration Guide

## ✅ Implementasi Selesai

Sistem absensi RFID sudah terintegrasi dengan database PostgreSQL!

## 🚀 Cara Menjalankan

### 1. Start Laravel Server

```bash
php artisan serve --host=0.0.0.0
```

**PENTING:** Gunakan `--host=0.0.0.0` agar bisa diakses dari ESP32!

### 2. Akses Website

Browser: `http://localhost:8000`

### 3. Setup ESP32

Lihat file `iot/README.md` untuk konfigurasi ESP32.

## 📋 Fitur Baru

### 1. Registrasi Kartu RFID via Website

**URL:** `/rfid/register`

**Flow:**
1. Admin klik "Aktifkan Mode Scan Kartu"
2. Mode registrasi aktif selama 60 detik
3. Siswa tap kartu RFID di ESP32
4. UID muncul di website secara real-time
5. Admin pilih siswa → assign UID
6. Selesai!

### 2. Absensi Otomatis

**Flow:**
1. Siswa tap kartu di ESP32
2. Sistem cek jam sekarang:
   - Jam 07:00-07:30 → Absen Masuk
   - Jam 14:00-15:00 → Absen Pulang
   - Luar jam → Ditolak
3. Validasi:
   - Kartu terdaftar?
   - Sudah absen hari ini?
   - Terlambat? (> 07:30)
4. Simpan ke database
5. Tampilkan hasil di LCD ESP32

### 3. Jam Absensi Dinamis

Jam absensi disimpan di database tabel `attendance_settings`.

**Default:**
- Check In: 07:00 - 07:30
- Check Out: 14:00 - 15:00
- Late Threshold: 07:30

**Ubah via database:**
```sql
UPDATE attendance_settings 
SET check_in_start = '06:30:00',
    check_in_end = '08:00:00',
    late_threshold = '07:00:00'
WHERE is_active = true;
```

ESP32 akan auto-sync setiap 5 menit!

## 🔗 API Endpoints

### For ESP32

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/v1/rfid/mode` | Cek mode (attendance/registration) |
| GET | `/api/v1/rfid/settings` | Ambil jam absensi |
| POST | `/api/v1/rfid/register` | Registrasi kartu baru |
| POST | `/api/v1/attendance/scan` | Absensi masuk/pulang |

### For Website (AJAX)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/v1/rfid/last-scanned` | Ambil UID terakhir yang di-scan |
| POST | `/api/v1/rfid/mode/activate` | Aktifkan mode registrasi |
| POST | `/api/v1/rfid/mode/deactivate` | Nonaktifkan mode registrasi |
| POST | `/api/v1/rfid/clear-scanned` | Hapus UID dari cache |

## 📊 Database Schema

### Tabel: `attendance_settings`

```sql
id                  BIGINT
check_in_start      TIME      -- Jam mulai absen masuk
check_in_end        TIME      -- Jam akhir absen masuk
check_out_start     TIME      -- Jam mulai absen pulang
check_out_end       TIME      -- Jam akhir absen pulang
late_threshold      TIME      -- Batas waktu terlambat
is_active           BOOLEAN   -- Setting aktif
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

### Tabel: `students` (Updated)

Field `rfid_uid` sudah ada, digunakan untuk menyimpan UID kartu RFID.

### Tabel: `attendances` (Updated)

Field `source` = 'rfid' untuk absensi dari ESP32.

## 🧪 Testing

### 1. Test API Registrasi

```bash
curl -X POST http://localhost:8000/api/v1/rfid/register \
  -H "Content-Type: application/json" \
  -d '{"rfid_uid":"A1B2C3D4"}'
```

Response:
```json
{
  "success": true,
  "message": "REGISTER_OK",
  "uid": "A1B2C3D4"
}
```

### 2. Test API Absensi

```bash
curl -X POST http://localhost:8000/api/v1/attendance/scan \
  -H "Content-Type: application/json" \
  -d '{"rfid_uid":"A1B2C3D4","type":"in"}'
```

Response:
```json
{
  "success": true,
  "code": "BERHASIL_MASUK",
  "message": "Selamat datang Nama Siswa",
  "student": "Nama Siswa",
  "status": "Hadir",
  "time": "07:15"
}
```

### 3. Test Mode Check

```bash
curl http://localhost:8000/api/v1/rfid/mode
```

Response:
```json
{
  "mode": "attendance",
  "timeout": 0
}
```

## 🔥 Firewall Windows

Jika ESP32 tidak bisa akses API, allow port 8000:

```powershell
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=TCP localport=8000
```

## 📝 Notes

- Cache menggunakan Laravel Cache (database driver)
- Mode registrasi timeout 60 detik
- ESP32 polling mode setiap 2 detik
- ESP32 sync settings setiap 5 menit
- Scan delay 10 detik (prevent double scan)

## 🎉 Selesai!

Sistem sudah siap digunakan. Tidak perlu Google Sheets lagi!
