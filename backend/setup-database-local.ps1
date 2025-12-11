# PowerShell Script untuk Setup Database Lokal dengan Docker
# Script ini akan setup MySQL dan SQL Server di Docker

Write-Host "`n=== Setup Database Lokal dengan Docker ===" -ForegroundColor Green
Write-Host "`nScript ini akan:" -ForegroundColor Yellow
Write-Host "  1. Start database containers (MySQL, SQL Server 1, SQL Server 2)" -ForegroundColor Cyan
Write-Host "  2. Create databases" -ForegroundColor Cyan
Write-Host "  3. Update .env file dengan konfigurasi database lokal" -ForegroundColor Cyan
Write-Host "`n"

$continue = Read-Host "Lanjutkan? (Y/N)"
if ($continue -ne "Y" -and $continue -ne "y") {
    Write-Host "Setup dibatalkan." -ForegroundColor Red
    exit
}

Write-Host "`n=== Step 1: Starting Database Containers ===" -ForegroundColor Green
Write-Host "Starting MySQL, SQL Server 1, and SQL Server 2..." -ForegroundColor Yellow

docker-compose up -d mysql sqlserver1 sqlserver2

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Error starting containers!" -ForegroundColor Red
    exit 1
}

Write-Host "✅ Containers started successfully!" -ForegroundColor Green
Write-Host "`nWaiting for databases to be ready (30 seconds)..." -ForegroundColor Yellow
Start-Sleep -Seconds 30

Write-Host "`n=== Step 2: Checking Container Status ===" -ForegroundColor Green
docker-compose ps mysql sqlserver1 sqlserver2

Write-Host "`n=== Step 3: Creating SQL Server Databases ===" -ForegroundColor Green

# Create database for SQL Server 1
Write-Host "Creating database for SQL Server 1..." -ForegroundColor Yellow
docker-compose exec -T sqlserver1 /opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "YourStrong@Passw0rd" -Q "CREATE DATABASE garudatest_sqlsrv1" 2>&1 | Out-Null
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Database 'garudatest_sqlsrv1' created" -ForegroundColor Green
} else {
    Write-Host "⚠️  Database might already exist or error occurred" -ForegroundColor Yellow
}

# Create database for SQL Server 2
Write-Host "Creating database for SQL Server 2..." -ForegroundColor Yellow
docker-compose exec -T sqlserver2 /opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "YourStrong@Passw0rd2" -Q "CREATE DATABASE garudatest_sqlsrv2" 2>&1 | Out-Null
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Database 'garudatest_sqlsrv2' created" -ForegroundColor Green
} else {
    Write-Host "⚠️  Database might already exist or error occurred" -ForegroundColor Yellow
}

Write-Host "`n=== Step 4: Updating .env File ===" -ForegroundColor Green

$envFile = ".env"
if (-not (Test-Path $envFile)) {
    Write-Host "File .env tidak ditemukan! Membuat dari env.example..." -ForegroundColor Yellow
    Copy-Item "env.example" $envFile
}

# Read current .env file
$envContent = Get-Content $envFile -Raw

# Update MySQL configuration
$envContent = $envContent -replace "DB_HOST=.*", "DB_HOST=mysql"
$envContent = $envContent -replace "DB_PORT=.*", "DB_PORT=3306"
$envContent = $envContent -replace "DB_DATABASE=.*", "DB_DATABASE=garudatest_db"
$envContent = $envContent -replace "DB_USERNAME=.*", "DB_USERNAME=garuda_user"
$envContent = $envContent -replace "DB_PASSWORD=.*", "DB_PASSWORD=garuda_password"

# Update SQL Server 1 configuration
$envContent = $envContent -replace "SQLSRV_HOST=.*", "SQLSRV_HOST=sqlserver1"
$envContent = $envContent -replace "SQLSRV_PORT=.*", "SQLSRV_PORT=1433"
$envContent = $envContent -replace "SQLSRV_DATABASE=.*", "SQLSRV_DATABASE=garudatest_sqlsrv1"
$envContent = $envContent -replace "SQLSRV_USERNAME=.*", "SQLSRV_USERNAME=sa"
$envContent = $envContent -replace "SQLSRV_PASSWORD=.*", "SQLSRV_PASSWORD=YourStrong@Passw0rd"

# Update SQL Server 2 configuration
$envContent = $envContent -replace "SQLSRV2_HOST=.*", "SQLSRV2_HOST=sqlserver2"
$envContent = $envContent -replace "SQLSRV2_PORT=.*", "SQLSRV2_PORT=1433"
$envContent = $envContent -replace "SQLSRV2_DATABASE=.*", "SQLSRV2_DATABASE=garudatest_sqlsrv2"
$envContent = $envContent -replace "SQLSRV2_USERNAME=.*", "SQLSRV2_USERNAME=sa"
$envContent = $envContent -replace "SQLSRV2_PASSWORD=.*", "SQLSRV2_PASSWORD=YourStrong@Passw0rd2"

# Write updated content
Set-Content -Path $envFile -Value $envContent -NoNewline

Write-Host "✅ File .env berhasil di-update dengan konfigurasi database lokal!" -ForegroundColor Green

Write-Host "`n=== Step 5: Summary ===" -ForegroundColor Green
Write-Host "`nDatabase Configuration:" -ForegroundColor Cyan
Write-Host "MySQL:" -ForegroundColor White
Write-Host "  Host: mysql (service name)" -ForegroundColor Gray
Write-Host "  Port: 3306" -ForegroundColor Gray
Write-Host "  Database: garudatest_db" -ForegroundColor Gray
Write-Host "  Username: garuda_user" -ForegroundColor Gray
Write-Host "  Password: garuda_password" -ForegroundColor Gray
Write-Host "`nSQL Server 1:" -ForegroundColor White
Write-Host "  Host: sqlserver1 (service name)" -ForegroundColor Gray
Write-Host "  Port: 1433" -ForegroundColor Gray
Write-Host "  Database: garudatest_sqlsrv1" -ForegroundColor Gray
Write-Host "  Username: sa" -ForegroundColor Gray
Write-Host "  Password: YourStrong@Passw0rd" -ForegroundColor Gray
Write-Host "`nSQL Server 2:" -ForegroundColor White
Write-Host "  Host: sqlserver2 (service name)" -ForegroundColor Gray
Write-Host "  Port: 1433" -ForegroundColor Gray
Write-Host "  Database: garudatest_sqlsrv2" -ForegroundColor Gray
Write-Host "  Username: sa" -ForegroundColor Gray
Write-Host "  Password: YourStrong@Passw0rd2" -ForegroundColor Gray

Write-Host "`n=== Next Steps ===" -ForegroundColor Yellow
Write-Host "1. Tunggu beberapa detik untuk database siap" -ForegroundColor Cyan
Write-Host "2. Test koneksi: docker-compose exec app php artisan db:test" -ForegroundColor Cyan
Write-Host "3. Run migrations: docker-compose exec app php artisan migrate" -ForegroundColor Cyan

Write-Host "`n✅ Setup database lokal selesai!" -ForegroundColor Green

