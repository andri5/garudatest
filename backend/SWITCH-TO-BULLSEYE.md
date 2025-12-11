# Switch ke Bullseye (Debian 11) - Jika Bookworm Masih Error

## 🔄 Cara Switch ke Bullseye

### **Opsi 1: Ganti Manual**

Edit `Dockerfile`:

1. **Baris 1**: Ganti `bookworm` menjadi `bullseye`
   ```dockerfile
   FROM php:8.4-fpm-bullseye
   ```

2. **Baris 77**: Ganti `debian/12/prod bookworm` menjadi `debian/11/prod bullseye`
   ```dockerfile
   echo "deb [arch=amd64,arm64,armhf signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/11/prod bullseye main"
   ```

### **Opsi 2: Gunakan File Alternatif**

```bash
# Backup Dockerfile saat ini
cp Dockerfile Dockerfile.bookworm

# Gunakan Dockerfile.bullseye
cp Dockerfile.bullseye Dockerfile

# Rebuild
docker-compose build --no-cache app
```

## 📊 Perbandingan

| Aspek | Bullseye (Debian 11) | Bookworm (Debian 12) |
|-------|---------------------|----------------------|
| **Stabilitas** | ✅ Sangat stabil (LTS) | ✅ Stabil (Current) |
| **PHP 8.4** | ✅ Support | ✅ Support |
| **Microsoft Repo** | ✅ Tersedia | ✅ Tersedia |
| **Package Versi** | Lebih lama (tapi stabil) | Lebih baru |
| **Support** | Long-term support | Current stable |

## 💡 Rekomendasi

1. **Coba dulu dengan perbaikan GPG key** (sudah diperbaiki di Dockerfile)
2. **Jika masih error**, baru switch ke Bullseye
3. **Bullseye lebih stabil** untuk production

## 🚀 Build dengan Bullseye

Setelah switch ke Bullseye:

```powershell
$env:DOCKER_BUILDKIT=1
$env:COMPOSE_DOCKER_CLI_BUILD=1
docker-compose build --no-cache app
```

