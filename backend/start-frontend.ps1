# PowerShell Script untuk Menjalankan Frontend Dev Server
# Script ini akan menjalankan Vite dev server untuk frontend

Write-Host "`n=== Frontend Dev Server ===" -ForegroundColor Green
Write-Host "`nStarting Vite dev server..." -ForegroundColor Yellow
Write-Host "`nBackend API: http://localhost:8000" -ForegroundColor Cyan
Write-Host "Frontend: http://localhost:5173" -ForegroundColor Cyan
Write-Host "`n⚠️  JANGAN tutup terminal ini!" -ForegroundColor Yellow
Write-Host "   Frontend dev server akan berjalan di terminal ini." -ForegroundColor Gray
Write-Host "`nTekan Ctrl+C untuk menghentikan server." -ForegroundColor Gray
Write-Host "`n" -ForegroundColor White

# Jalankan Vite dev server
docker-compose exec app npm run dev

