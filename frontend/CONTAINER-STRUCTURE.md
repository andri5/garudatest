# Struktur Container Docker

## Penjelasan Container

Aplikasi ini menggunakan **2 container terpisah**:

### 1. Container `nginx` (super-app-nginx)
- **Image**: `nginx:alpine`
- **Fungsi**: Web server (menangani HTTP request)
- **Port**: 8000:80
- **Tidak berisi PHP** ❌
- **Berisi**: Hanya Nginx web server

### 2. Container `app` (super-app-php)
- **Image**: `super-app/php` (custom dari Dockerfile)
- **Fungsi**: PHP-FPM (menjalankan PHP code)
- **Port**: 9000 (internal, untuk komunikasi dengan Nginx)
- **Berisi**: PHP 8.3, Composer, sqlsrv extension ✅

## Cara Kerja:

```
Browser → Nginx (port 8000) → PHP-FPM (port 9000) → Laravel
```

1. Browser mengakses `http://localhost:8000`
2. Nginx menerima request
3. Nginx mengirim request ke PHP-FPM di container `app` (port 9000)
4. PHP-FPM menjalankan Laravel code
5. Hasil dikembalikan ke Nginx, lalu ke browser

## Perintah yang Benar:

### ✅ Untuk menjalankan PHP command:
```bash
# Exec ke container APP (yang berisi PHP)
docker-compose exec app php -v
docker-compose exec app php artisan list
docker-compose exec app composer install
```

### ❌ SALAH - Exec ke container Nginx:
```bash
# Nginx tidak punya PHP!
docker-compose exec nginx php -v
# Output: /bin/sh: php: not found
```

### ✅ Untuk cek konfigurasi Nginx:
```bash
# Exec ke container Nginx
docker-compose exec nginx nginx -t
docker-compose exec nginx cat /etc/nginx/conf.d/app.conf
```

## Ringkasan:

| Container | Berisi | Perintah yang bisa dijalankan |
|-----------|--------|-------------------------------|
| `nginx` | Nginx web server | `nginx -t`, `nginx -s reload` |
| `app` | PHP 8.3, Composer, Laravel | `php -v`, `php artisan`, `composer` |

## Tips:

**Untuk menjalankan perintah Laravel/PHP, selalu gunakan:**
```bash
docker-compose exec app <command>
```

**Contoh:**
```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker
docker-compose exec app composer install
docker-compose exec app php -v
```

