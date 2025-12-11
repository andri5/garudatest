# Keamanan Instalasi Laravel di Staging

## ✅ Laravel AMAN - Tidak Akan Install 2x

Script `install-laravel.sh` dan `install-laravel.bat` sudah dirancang untuk mencegah instalasi ganda.

## Mekanisme Proteksi:

### 1. **Cek Apakah Laravel Sudah Terinstall**
```bash
# Cek apakah file artisan sudah ada
if [ -f "artisan" ]; then
    echo "✅ Laravel sudah terinstall!"
    exit 0  # Langsung keluar, tidak install lagi
fi
```

### 2. **Install ke Folder Temp, Lalu Pindah ke Root**
```bash
# Install ke temp-install (bukan temp-laravel)
composer create-project laravel/laravel:^12.0 temp-install

# Copy file ke root
cp -r temp-install/* ..

# Hapus folder temp-install OTOMATIS
rm -rf temp-install
```

### 3. **Hasil Akhir di Staging:**
```
super-app/
├── app/              ← Laravel (langsung di root)
├── bootstrap/        ← Laravel
├── config/           ← Laravel
├── artisan           ← Laravel
├── composer.json     ← Laravel
├── Dockerfile        ← Docker (tidak tertimpa)
└── docker-compose.yml ← Docker (tidak tertimpa)

❌ TIDAK ADA folder temp-install atau temp-laravel
```

## Skenario di Staging:

### Skenario 1: Instalasi Pertama Kali
```bash
# 1. Clone repo (hanya file Docker)
git clone <repo> super-app
cd super-app

# 2. Build Docker
docker-compose up -d --build

# 3. Install Laravel
bash install-laravel.sh

# Hasil: Laravel langsung di root, temp-install sudah dihapus
```

### Skenario 2: Laravel Sudah Terinstall
```bash
# Jika artisan sudah ada
bash install-laravel.sh

# Output:
# ✅ Laravel sudah terinstall!
# Script langsung exit, tidak install lagi
```

### Skenario 3: Re-run Script (Idempotent)
```bash
# Bisa dijalankan berkali-kali, aman!
bash install-laravel.sh  # ✅ Laravel sudah terinstall!
bash install-laravel.sh  # ✅ Laravel sudah terinstall!
bash install-laravel.sh  # ✅ Laravel sudah terinstall!
```

## Perbedaan dengan Instalasi Manual Tadi:

| Aspek | Instalasi Manual (Tadi) | Script install-laravel.sh |
|-------|-------------------------|---------------------------|
| Folder temp | `temp-laravel` (manual) | `temp-install` (otomatis) |
| Hapus temp | Manual (`rm -rf temp-laravel`) | **Otomatis dihapus** |
| Cek instalasi | Tidak ada | **Ada cek file artisan** |
| Re-run | Bisa install 2x | **Aman, tidak install 2x** |

## Kesimpulan:

✅ **Laravel AMAN di staging**
- Tidak akan install 2x (ada cek file artisan)
- Folder temp otomatis dihapus setelah copy
- Struktur akhir: Laravel langsung di root
- Script bisa dijalankan berkali-kali tanpa masalah (idempotent)

## Best Practice untuk Staging:

1. **Pertama kali setup:**
   ```bash
   docker-compose up -d --build
   bash install-laravel.sh
   ```

2. **Jika sudah ada Laravel:**
   - Script akan skip instalasi
   - Tidak perlu khawatir install 2x

3. **Untuk update Laravel:**
   - Update via `composer update` di dalam container
   - Jangan jalankan `install-laravel.sh` lagi

