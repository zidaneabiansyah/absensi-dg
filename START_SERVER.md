# 🚀 Cara Menjalankan Server untuk ESP32

## ⚠️ PENTING!

Untuk ESP32 bisa mengakses API, Laravel harus running dengan `--host=0.0.0.0`

## 📝 Langkah-langkah:

### 1. Buka 2 Terminal/CMD di folder `absensi-dg`

**Terminal 1 - Laravel Server:**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

**Terminal 2 - Vite Dev Server (untuk CSS/JS):**
```bash
composer run dev
```

Atau jika pakai npm langsung:
```bash
npm run dev
```

### 2. Output yang benar:

**Terminal 1:**
```
INFO  Server running on [http://0.0.0.0:8000].

Press Ctrl+C to stop the server
```

**Terminal 2:**
```
VITE v5.x.x  ready in xxx ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
```

### 3. Cek IP Komputer Anda

**Windows:**
```bash
ipconfig
```

Cari "IPv4 Address", contoh: `192.168.1.100`

### 4. Update ESP32 Code

Edit file `iot/esp32_rfid_absensi.ino`:

```cpp
const char* API_BASE_URL = "http://192.168.1.100:8000/api/v1";
```

Ganti `192.168.1.100` dengan IP komputer Anda!

### 5. Test dari Browser

Buka: `http://localhost:8000`

### 6. Test API dari ESP32

ESP32 akan akses: `http://192.168.1.100:8000/api/v1/...`

## 🔥 Firewall Windows

Jika ESP32 tidak bisa connect, allow port 8000:

```powershell
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=TCP localport=8000
```
