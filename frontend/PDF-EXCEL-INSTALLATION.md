# PDF & Excel - Penjelasan Lengkap

## 🔍 Perbedaan: Tools vs Library

### **✅ Yang Sudah Terinstall di Docker (Tools)**
- ✅ **wkhtmltopdf** - Tool untuk convert HTML ke PDF (sudah terinstall)
- ✅ **Extension PHP** - imagick, gd, dll (sudah terinstall)

### **❌ Yang Perlu Diinstall Lagi (Library PHP)**
- ❌ **dompdf** - Library PHP untuk PDF (perlu install via Composer)
- ❌ **snappy** - Laravel wrapper untuk wkhtmltopdf (perlu install via Composer)
- ❌ **PhpSpreadsheet** - Library PHP untuk Excel (perlu install via Composer)
- ❌ **Maatwebsite Excel** - Laravel wrapper untuk Excel (perlu install via Composer)

**Kesimpulan**: Tools sudah ada, tapi **library PHP perlu diinstall via Composer** setelah Laravel terinstall.

---

## 📦 Kapan Install?

### **Urutan Install:**
1. ✅ **Docker sudah siap** (sudah selesai)
2. ⏳ **Install Laravel** (langkah berikutnya)
3. ⏳ **Install Library PDF/Excel** (setelah Laravel terinstall)

**Jadi**: Install library PDF/Excel **SETELAH** Laravel terinstall, bukan sekarang.

---

## 🚀 Cara Install (Setelah Laravel Terinstall)

### **1. Install Library PDF**

#### **Opsi A: dompdf (Recommended untuk pemula)**
```powershell
# Install dompdf
docker-compose exec app composer require dompdf/dompdf

# Kelebihan:
# - Mudah digunakan
# - Pure PHP (tidak perlu external tool)
# - Support CSS styling
# - Cocok untuk invoice, report sederhana
```

**Contoh penggunaan:**
```php
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml('<h1>Hello World</h1>');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("document.pdf");
```

#### **Opsi B: snappy (Menggunakan wkhtmltopdf yang sudah terinstall)**
```powershell
# Install snappy
docker-compose exec app composer require barryvdh/laravel-snappy

# Kelebihan:
# - Lebih powerful untuk complex HTML
# - Support JavaScript rendering
# - Menggunakan wkhtmltopdf yang sudah terinstall
# - Cocok untuk report kompleks dengan chart/graph
```

**Contoh penggunaan:**
```php
use Barryvdh\Snappy\Facades\SnappyPdf;

$pdf = SnappyPdf::loadView('invoice', $data);
return $pdf->download('invoice.pdf');
```

**Rekomendasi**: 
- **dompdf** jika butuh PDF sederhana (invoice, report)
- **snappy** jika butuh PDF kompleks (dengan JavaScript, chart)

---

### **2. Install Library Excel**

#### **Opsi A: PhpSpreadsheet (Recommended - lebih fleksibel)**
```powershell
# Install PhpSpreadsheet
docker-compose exec app composer require phpoffice/phpspreadsheet

# Kelebihan:
# - Sangat fleksibel
# - Support banyak format (XLSX, XLS, CSV, ODS)
# - Support formulas, charts, styling
# - Bisa read dan write
```

**Contoh penggunaan:**
```php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World');
$sheet->setCellValue('B1', 'Excel!');

$writer = new Xlsx($spreadsheet);
$writer->save('hello_world.xlsx');
```

#### **Opsi B: Maatwebsite Excel (Laravel wrapper - lebih mudah)**
```powershell
# Install Maatwebsite Excel
docker-compose exec app composer require maatwebsite/excel

# Kelebihan:
# - Laravel-specific (lebih mudah)
# - Import/Export yang simple
# - Queue support
# - Auto mapping dari/to Model
```

**Contoh penggunaan:**
```php
use Maatwebsite\Excel\Facades\Excel;

// Export
Excel::store(new UsersExport, 'users.xlsx');

// Import
Excel::import(new UsersImport, 'users.xlsx');
```

**Rekomendasi**: 
- **Maatwebsite Excel** jika pakai Laravel dan butuh import/export sederhana
- **PhpSpreadsheet** jika butuh kontrol lebih detail (formulas, charts, styling)

---

## 📋 Checklist Install

### **Setelah Laravel Terinstall:**

```powershell
# 1. Install Laravel (jika belum)
.\install-laravel.bat

# 2. Install Library PDF (pilih salah satu)
docker-compose exec app composer require dompdf/dompdf
# ATAU
docker-compose exec app composer require barryvdh/laravel-snappy

# 3. Install Library Excel (pilih salah satu)
docker-compose exec app composer require phpoffice/phpspreadsheet
# ATAU
docker-compose exec app composer require maatwebsite/excel
```

---

## 🎯 Rekomendasi Kombinasi

### **Kombinasi 1: Sederhana (Recommended untuk pemula)**
```powershell
# PDF: dompdf (mudah)
docker-compose exec app composer require dompdf/dompdf

# Excel: Maatwebsite Excel (Laravel-friendly)
docker-compose exec app composer require maatwebsite/excel
```

### **Kombinasi 2: Powerful (Untuk kebutuhan kompleks)**
```powershell
# PDF: snappy (menggunakan wkhtmltopdf)
docker-compose exec app composer require barryvdh/laravel-snappy

# Excel: PhpSpreadsheet (fleksibel)
docker-compose exec app composer require phpoffice/phpspreadsheet
```

### **Kombinasi 3: Mix (Recommended untuk production)**
```powershell
# PDF: snappy (untuk report kompleks)
docker-compose exec app composer require barryvdh/laravel-snappy

# Excel: Maatwebsite Excel (untuk import/export sederhana)
docker-compose exec app composer require maatwebsite/excel
```

---

## 💡 Tips

1. **Install sesuai kebutuhan** - Tidak perlu install semua, pilih yang sesuai
2. **dompdf vs snappy** - dompdf untuk sederhana, snappy untuk kompleks
3. **Maatwebsite vs PhpSpreadsheet** - Maatwebsite untuk Laravel, PhpSpreadsheet untuk kontrol detail
4. **Install setelah Laravel** - Library ini perlu Laravel sudah terinstall dulu

---

## 📚 Dokumentasi Library

- **dompdf**: https://github.com/dompdf/dompdf
- **snappy**: https://github.com/barryvdh/laravel-snappy
- **PhpSpreadsheet**: https://phpspreadsheet.readthedocs.io/
- **Maatwebsite Excel**: https://docs.laravel-excel.com/

---

## 🔄 Summary

| Item | Status | Kapan Install |
|------|--------|--------------|
| **wkhtmltopdf** | ✅ Sudah terinstall | - |
| **Extension PHP** | ✅ Sudah terinstall | - |
| **dompdf** | ❌ Perlu install | Setelah Laravel |
| **snappy** | ❌ Perlu install | Setelah Laravel |
| **PhpSpreadsheet** | ❌ Perlu install | Setelah Laravel |
| **Maatwebsite Excel** | ❌ Perlu install | Setelah Laravel |

**TL;DR**: Tools sudah ada, tapi library PHP perlu diinstall via Composer **SETELAH** Laravel terinstall. Pilih sesuai kebutuhan!

