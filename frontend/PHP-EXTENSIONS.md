# PHP Extensions & Tools untuk PDF/Excel

## ✅ Extension yang Sudah Terinstall

### **Image Processing**
- ✅ **imagick** - ImageMagick extension untuk manipulasi gambar
- ✅ **gd** - GD library untuk image processing

### **Database**
- ✅ **pdo** - PDO database abstraction
- ✅ **pdo_mysql** - MySQL driver
- ✅ **pdo_sqlsrv** - SQL Server driver
- ✅ **sqlsrv** - SQL Server extension

### **String & Encoding**
- ✅ **mbstring** - Multi-byte string handling
- ✅ **intl** - Internationalization (untuk date, number formatting)
- ✅ **iconv** - Character encoding conversion

### **Network & Communication**
- ✅ **soap** - SOAP API support
- ✅ **sockets** - Socket programming
- ✅ **ftp** - FTP client
- ⚠️ **imap** - IMAP email support (tidak tersedia di PHP 8.4, gunakan library PHP seperti Webklex/php-imap)
- ✅ **ldap** - LDAP directory support
- ✅ **curl** - HTTP client

### **Utilities**
- ✅ **bcmath** - Arbitrary precision mathematics
- ✅ **gmp** - GNU Multiple Precision arithmetic
- ✅ **calendar** - Calendar functions
- ✅ **exif** - EXIF image metadata
- ✅ **pcntl** - Process control
- ✅ **zip** - ZIP archive support
- ✅ **xml** - XML parsing
- ✅ **tidy** - HTML/XML tidying
- ✅ **opcache** - OPcache for performance

### **Tools untuk PDF Generation**
- ✅ **wkhtmltopdf** - Convert HTML to PDF
- ✅ **xvfb** - Virtual framebuffer (untuk headless rendering)
- ✅ **fonts-liberation** - Fonts untuk PDF
- ✅ **fonts-dejavu-core** - Fonts untuk PDF
- ✅ **fontconfig** - Font configuration

## 📚 Library PHP untuk PDF/Excel (Install via Composer)

### **PDF Generation**

#### 1. **dompdf** (Recommended untuk HTML to PDF)
```bash
composer require dompdf/dompdf
```
- Convert HTML/CSS ke PDF
- Mudah digunakan
- Support CSS styling

#### 2. **snappy** (Menggunakan wkhtmltopdf)
```bash
composer require barryvdh/laravel-snappy
```
- Menggunakan wkhtmltopdf yang sudah terinstall
- Lebih powerful untuk complex HTML
- Support JavaScript rendering

#### 3. **mpdf** (Alternative)
```bash
composer require mpdf/mpdf
```
- Pure PHP (tidak perlu external tool)
- Support advanced features

### **Excel Generation**

#### 1. **PhpSpreadsheet** (Recommended)
```bash
composer require phpoffice/phpspreadsheet
```
- Read/Write Excel files (XLSX, XLS, CSV)
- Support formulas, charts, styling
- Most popular library

#### 2. **Maatwebsite Excel** (Laravel wrapper untuk PhpSpreadsheet)
```bash
composer require maatwebsite/excel
```
- Laravel-specific wrapper
- Easy import/export
- Queue support

## 🚀 Contoh Penggunaan

### **PDF dengan dompdf**
```php
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml('<h1>Hello World</h1>');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("document.pdf");
```

### **PDF dengan Snappy (wkhtmltopdf)**
```php
use Barryvdh\Snappy\Facades\SnappyPdf;

$pdf = SnappyPdf::loadView('invoice', $data);
return $pdf->download('invoice.pdf');
```

### **Excel dengan PhpSpreadsheet**
```php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World');
$writer = new Xlsx($spreadsheet);
$writer->save('hello_world.xlsx');
```

### **Excel dengan Maatwebsite Excel**
```php
use Maatwebsite\Excel\Facades\Excel;

// Export
Excel::store(new UsersExport, 'users.xlsx');

// Import
Excel::import(new UsersImport, 'users.xlsx');
```

## 🔍 Verifikasi Extension

```bash
# Cek semua extension
docker-compose exec app php -m

# Cek extension tertentu
docker-compose exec app php -m | grep imagick
docker-compose exec app php -m | grep intl
docker-compose exec app php -m | grep soap

# Cek wkhtmltopdf
docker-compose exec app wkhtmltopdf --version
```

## 📝 Catatan

1. **Imagick** sekarang terinstall secara langsung (tidak conditional)
2. **wkhtmltopdf** sudah terinstall untuk PDF generation
3. **Extension lengkap** sudah ditambahkan untuk kebutuhan umum Laravel
4. **Library PHP** untuk PDF/Excel perlu diinstall via Composer setelah Laravel terinstall

## 🔄 Rebuild Docker Image

Setelah update Dockerfile:

```powershell
# Rebuild image
docker-compose build --no-cache app

# Restart container
docker-compose up -d

# Verifikasi
docker-compose exec app php -m
```

