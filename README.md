# Sistem Absensi Siswa

Aplikasi web untuk manajemen absensi siswa berbasis Laravel yang menyediakan fitur pencatatan kehadiran, pengelolaan data siswa dan kelas, serta pelaporan absensi secara komprehensif.

## Deskripsi

Sistem Absensi Siswa adalah solusi digital untuk mengelola presensi siswa di institusi pendidikan. Aplikasi ini memungkinkan operator untuk mencatat kehadiran siswa secara manual atau melalui integrasi RFID, mengelola data master (kelas dan siswa), serta menghasilkan laporan absensi dalam berbagai format.

## Fitur Utama

- **Manajemen Pengguna**: Sistem autentikasi dengan role admin dan operator
- **Manajemen Kelas**: CRUD data kelas dengan informasi wali kelas dan tahun ajaran
- **Manajemen Siswa**: CRUD data siswa dengan dukungan NIS, NISN, dan RFID UID
- **Pencatatan Absensi**: Input kehadiran manual dengan 5 status (Hadir, Izin, Sakit, Alpha, Terlambat)
- **Dashboard Statistik**: Ringkasan real-time kehadiran siswa per hari
- **Pencarian & Filter**: Fitur pencarian dan filter data berdasarkan berbagai kriteria
- **Laporan Absensi**: Generate laporan harian, bulanan, per kelas, dan per siswa
- **Export Data**: Export laporan ke format PDF dan Excel
- **Responsive Design**: Antarmuka yang optimal di desktop dan mobile

## Teknologi

- **Framework**: Laravel 12
- **Database**: SQLite (default), support MySQL/PostgreSQL
- **Frontend**: Blade Templates, Tailwind CSS 4.0
- **Build Tool**: Vite
- **PHP Version**: 8.2+

## Instalasi

### Prasyarat

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- SQLite atau MySQL/PostgreSQL

### Langkah Instalasi

1. Clone repository dan masuk ke direktori project
```bash
git clone <repository-url>
cd <project-folder>
```

2. Install dependencies PHP dan JavaScript
```bash
composer install
npm install
```

3. Konfigurasi environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Setup database
```bash
# Untuk SQLite
touch database/database.sqlite

# Jalankan migrations dan seeder
php artisan migrate:fresh --seed
```

5. Build frontend assets
```bash
npm run build
```

6. Jalankan development server
```bash
php artisan serve
```

Aplikasi dapat diakses di `http://localhost:8000`

## Kredensial Default

Setelah menjalankan seeder, gunakan kredensial berikut untuk login:

**Administrator**
- Email: admin@admin.com
- Password: password

**Operator**
- Email: operator@admin.com
- Password: password

## Struktur Database

**users**: Data pengguna sistem dengan role dan status
**classes**: Data kelas dengan wali kelas dan tahun ajaran
**students**: Data siswa dengan NIS, NISN, kelas, dan RFID UID
**attendances**: Record absensi siswa dengan tanggal, status, dan waktu

## Status Absensi

- **H** (Hadir): Siswa hadir tepat waktu
- **I** (Izin): Siswa tidak hadir dengan izin
- **S** (Sakit): Siswa tidak hadir karena sakit
- **A** (Alpha): Siswa tidak hadir tanpa keterangan
- **T** (Terlambat): Siswa hadir terlambat

## Keamanan

Aplikasi dilengkapi dengan fitur keamanan standar Laravel:
- CSRF Protection
- Password Hashing (Bcrypt)
- Rate Limiting untuk login
- Session Management
- Input Validation
- XSS Prevention

## Development

Untuk development dengan hot reload:
```bash
npm run dev
```

Untuk menjalankan tests:
```bash
php artisan test
```

## Roadmap

- Integrasi API untuk perangkat RFID
- Role-based access control yang lebih granular
- Import data siswa via CSV/Excel
- Notifikasi email untuk orang tua
- Mobile app untuk input absensi
- Dashboard analytics yang lebih advanced

## Lisensi

MIT License

## Kontribusi

Kontribusi dalam bentuk pull request atau issue report sangat diterima. Pastikan untuk mengikuti coding standards dan menambahkan tests untuk fitur baru.
