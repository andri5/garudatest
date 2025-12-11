# PowerShell Script untuk Setup Database Credentials
# Script ini membantu mengisi kredensial database di file .env

Write-Host "`n=== Database Configuration Setup ===" -ForegroundColor Green
Write-Host "`nScript ini akan membantu Anda mengisi kredensial database di file .env" -ForegroundColor Yellow
Write-Host "Pastikan Anda sudah memiliki informasi berikut:" -ForegroundColor Yellow
Write-Host "  - MySQL: Host, Database, Username, Password" -ForegroundColor Cyan
Write-Host "  - SQL Server 1: Host, Database, Username, Password" -ForegroundColor Cyan
Write-Host "  - SQL Server 2: Host, Database, Username, Password" -ForegroundColor Cyan
Write-Host "`n"

$continue = Read-Host "Lanjutkan? (Y/N)"
if ($continue -ne "Y" -and $continue -ne "y") {
    Write-Host "Setup dibatalkan." -ForegroundColor Red
    exit
}

$envFile = ".env"
if (-not (Test-Path $envFile)) {
    Write-Host "File .env tidak ditemukan! Membuat dari env.example..." -ForegroundColor Yellow
    Copy-Item "env.example" $envFile
}

Write-Host "`n=== MySQL Configuration ===" -ForegroundColor Green
$mysqlHost = Read-Host "MySQL Host (contoh: db.example.com atau 192.168.1.100)"
$mysqlPort = Read-Host "MySQL Port [3306]"
if ([string]::IsNullOrWhiteSpace($mysqlPort)) { $mysqlPort = "3306" }
$mysqlDatabase = Read-Host "MySQL Database Name"
$mysqlUsername = Read-Host "MySQL Username"
$mysqlPassword = Read-Host "MySQL Password" -AsSecureString
$mysqlPasswordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($mysqlPassword))

Write-Host "`n=== SQL Server 1 Configuration ===" -ForegroundColor Green
$sqlsrvHost = Read-Host "SQL Server 1 Host (contoh: sqlserver1.example.com atau 192.168.1.101)"
$sqlsrvPort = Read-Host "SQL Server 1 Port [1433]"
if ([string]::IsNullOrWhiteSpace($sqlsrvPort)) { $sqlsrvPort = "1433" }
$sqlsrvDatabase = Read-Host "SQL Server 1 Database Name"
$sqlsrvUsername = Read-Host "SQL Server 1 Username"
$sqlsrvPassword = Read-Host "SQL Server 1 Password" -AsSecureString
$sqlsrvPasswordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($sqlsrvPassword))

Write-Host "`n=== SQL Server 2 Configuration ===" -ForegroundColor Green
$sqlsrv2Host = Read-Host "SQL Server 2 Host (contoh: sqlserver2.example.com atau 192.168.1.102)"
$sqlsrv2Port = Read-Host "SQL Server 2 Port [1433]"
if ([string]::IsNullOrWhiteSpace($sqlsrv2Port)) { $sqlsrv2Port = "1433" }
$sqlsrv2Database = Read-Host "SQL Server 2 Database Name"
$sqlsrv2Username = Read-Host "SQL Server 2 Username"
$sqlsrv2Password = Read-Host "SQL Server 2 Password" -AsSecureString
$sqlsrv2PasswordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($sqlsrv2Password))

Write-Host "`n=== Summary ===" -ForegroundColor Green
Write-Host "MySQL:" -ForegroundColor Cyan
Write-Host "  Host: $mysqlHost" -ForegroundColor White
Write-Host "  Port: $mysqlPort" -ForegroundColor White
Write-Host "  Database: $mysqlDatabase" -ForegroundColor White
Write-Host "  Username: $mysqlUsername" -ForegroundColor White
Write-Host "`nSQL Server 1:" -ForegroundColor Cyan
Write-Host "  Host: $sqlsrvHost" -ForegroundColor White
Write-Host "  Port: $sqlsrvPort" -ForegroundColor White
Write-Host "  Database: $sqlsrvDatabase" -ForegroundColor White
Write-Host "  Username: $sqlsrvUsername" -ForegroundColor White
Write-Host "`nSQL Server 2:" -ForegroundColor Cyan
Write-Host "  Host: $sqlsrv2Host" -ForegroundColor White
Write-Host "  Port: $sqlsrv2Port" -ForegroundColor White
Write-Host "  Database: $sqlsrv2Database" -ForegroundColor White
Write-Host "  Username: $sqlsrv2Username" -ForegroundColor White

$confirm = Read-Host "`nKonfirmasi dan update file .env? (Y/N)"
if ($confirm -ne "Y" -and $confirm -ne "y") {
    Write-Host "Update dibatalkan." -ForegroundColor Red
    exit
}

# Read current .env file
$envContent = Get-Content $envFile -Raw

# Update MySQL configuration
$envContent = $envContent -replace "DB_HOST=.*", "DB_HOST=$mysqlHost"
$envContent = $envContent -replace "DB_PORT=.*", "DB_PORT=$mysqlPort"
$envContent = $envContent -replace "DB_DATABASE=.*", "DB_DATABASE=$mysqlDatabase"
$envContent = $envContent -replace "DB_USERNAME=.*", "DB_USERNAME=$mysqlUsername"
$envContent = $envContent -replace "DB_PASSWORD=.*", "DB_PASSWORD=$mysqlPasswordPlain"

# Update SQL Server 1 configuration
$envContent = $envContent -replace "SQLSRV_HOST=.*", "SQLSRV_HOST=$sqlsrvHost"
$envContent = $envContent -replace "SQLSRV_PORT=.*", "SQLSRV_PORT=$sqlsrvPort"
$envContent = $envContent -replace "SQLSRV_DATABASE=.*", "SQLSRV_DATABASE=$sqlsrvDatabase"
$envContent = $envContent -replace "SQLSRV_USERNAME=.*", "SQLSRV_USERNAME=$sqlsrvUsername"
$envContent = $envContent -replace "SQLSRV_PASSWORD=.*", "SQLSRV_PASSWORD=$sqlsrvPasswordPlain"

# Update SQL Server 2 configuration
$envContent = $envContent -replace "SQLSRV2_HOST=.*", "SQLSRV2_HOST=$sqlsrv2Host"
$envContent = $envContent -replace "SQLSRV2_PORT=.*", "SQLSRV2_PORT=$sqlsrv2Port"
$envContent = $envContent -replace "SQLSRV2_DATABASE=.*", "SQLSRV2_DATABASE=$sqlsrv2Database"
$envContent = $envContent -replace "SQLSRV2_USERNAME=.*", "SQLSRV2_USERNAME=$sqlsrv2Username"
$envContent = $envContent -replace "SQLSRV2_PASSWORD=.*", "SQLSRV2_PASSWORD=$sqlsrv2PasswordPlain"

# Write updated content
Set-Content -Path $envFile -Value $envContent -NoNewline

Write-Host "`n✅ File .env berhasil di-update!" -ForegroundColor Green
Write-Host "`nLangkah selanjutnya:" -ForegroundColor Yellow
Write-Host "  1. Test koneksi: docker-compose exec app php artisan db:test" -ForegroundColor Cyan
Write-Host "  2. Run migrations: docker-compose exec app php artisan migrate" -ForegroundColor Cyan

