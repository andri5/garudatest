# 📋 Plan Git - Push ke Repository GitHub

**Repository**: https://github.com/andri5/garudatest.git

---

## 🎯 Tujuan / Objective

Dokumen ini berisi langkah-langkah untuk menginisialisasi Git dan push kode ke repository GitHub.

This document contains steps to initialize Git and push code to GitHub repository.

---

## ✅ Status Saat Ini / Current Status

- ✅ Git sudah diinisialisasi (branch: master)
- ✅ Repository GitHub sudah dibuat (kosong)
- ✅ File `.gitignore` sudah ada di `backend/` dan `frontend/`
- ⏳ Belum ada commit
- ⏳ Belum ada remote repository yang ditambahkan

---

## 📝 Langkah-langkah / Steps

### 1. Konfigurasi Git (Jika Belum) / Configure Git (If Not Done)

```bash
# Set nama dan email (ganti dengan data Anda)
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

**Catatan**: Jika sudah dikonfigurasi, skip langkah ini.

**Note**: If already configured, skip this step.

---

### 2. Buat .gitignore di Root (Opsional) / Create Root .gitignore (Optional)

Jika ingin menambahkan `.gitignore` di root project:

```bash
# Buat file .gitignore di root
echo "# OS Files" > .gitignore
echo ".DS_Store" >> .gitignore
echo "Thumbs.db" >> .gitignore
echo "" >> .gitignore
echo "# IDE" >> .gitignore
echo ".vscode/" >> .gitignore
echo ".idea/" >> .gitignore
echo "" >> .gitignore
echo "# Logs" >> .gitignore
echo "*.log" >> .gitignore
```

**Catatan**: Ini opsional karena `backend/` dan `frontend/` sudah punya `.gitignore`.

**Note**: This is optional since `backend/` and `frontend/` already have `.gitignore`.

---

### 3. Tambahkan Remote Repository / Add Remote Repository

```bash
# Tambahkan remote origin
git remote add origin https://github.com/andri5/garudatest.git

# Verifikasi remote sudah ditambahkan
git remote -v
```

**Output yang diharapkan**:
```
origin  https://github.com/andri5/garudatest.git (fetch)
origin  https://github.com/andri5/garudatest.git (push)
```

---

### 4. Tambahkan Semua File ke Staging / Add All Files to Staging

```bash
# Tambahkan semua file
git add .

# Atau tambahkan secara spesifik
git add backend/
git add frontend/
git add plan-git.md
```

**Catatan**: File `.env`, `vendor/`, `node_modules/` akan otomatis diabaikan karena ada di `.gitignore`.

**Note**: Files like `.env`, `vendor/`, `node_modules/` will be automatically ignored due to `.gitignore`.

---

### 5. Buat Commit Pertama / Create First Commit

```bash
# Buat commit dengan pesan
git commit -m "Initial commit: Laravel backend and frontend setup"
```

**Pesan alternatif**:
- `"feat: initial project setup"`
- `"chore: initial commit"`
- `"Initial commit"`

---

### 6. Push ke GitHub / Push to GitHub

#### Opsi A: Push ke Branch Master (Jika Branch Utama adalah Master)

```bash
# Push ke master
git push -u origin master
```

#### Opsi B: Push ke Branch Main (Jika Branch Utama adalah Main)

```bash
# Rename branch ke main (jika perlu)
git branch -M main

# Push ke main
git push -u origin main
```

**Catatan**: GitHub sekarang default menggunakan `main` sebagai branch utama. Jika repository Anda menggunakan `main`, gunakan Opsi B.

**Note**: GitHub now defaults to `main` as the primary branch. If your repository uses `main`, use Option B.

---

### 7. Verifikasi / Verify

1. Buka browser dan kunjungi: https://github.com/andri5/garudatest
2. Pastikan semua file sudah ter-upload
3. Cek struktur folder `backend/` dan `frontend/`

---

## 🔐 Autentikasi / Authentication

### Menggunakan Personal Access Token (PAT)

Jika diminta username dan password:

1. **Buat Personal Access Token**:
   - GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
   - Generate new token (classic)
   - Beri nama: `garudatest-access`
   - Pilih scope: `repo` (full control)
   - Generate dan **copy token** (hanya muncul sekali!)

2. **Gunakan Token sebagai Password**:
   ```
   Username: your-github-username
   Password: [paste-token-di-sini]
   ```

### Menggunakan SSH (Alternatif)

```bash
# Ubah remote ke SSH
git remote set-url origin git@github.com:andri5/garudatest.git

# Push
git push -u origin master
```

**Catatan**: Perlu setup SSH key terlebih dahulu.

**Note**: Requires SSH key setup first.

---

## 🚨 Troubleshooting / Masalah Umum

### Error: "remote origin already exists"

```bash
# Hapus remote yang ada
git remote remove origin

# Tambahkan lagi
git remote add origin https://github.com/andri5/garudatest.git
```

### Error: "failed to push some refs"

```bash
# Pull dulu (jika ada perubahan di remote)
git pull origin master --allow-unrelated-histories

# Atau force push (HATI-HATI! Hanya jika yakin)
git push -u origin master --force
```

### Error: "authentication failed"

- Pastikan Personal Access Token sudah dibuat
- Pastikan token belum expired
- Gunakan token sebagai password, bukan password GitHub

---

## 📋 Checklist / Checklist

Sebelum push, pastikan:

- [ ] Git sudah dikonfigurasi (nama dan email)
- [ ] Remote repository sudah ditambahkan
- [ ] File `.env` tidak ter-commit (ada di `.gitignore`)
- [ ] Folder `vendor/` tidak ter-commit (ada di `.gitignore`)
- [ ] Folder `node_modules/` tidak ter-commit (ada di `.gitignore`)
- [ ] Semua file penting sudah ditambahkan
- [ ] Commit message sudah jelas
- [ ] Personal Access Token sudah siap (jika perlu)

---

## 🔄 Workflow Selanjutnya / Next Workflow

Setelah initial commit, workflow normal:

```bash
# 1. Cek status
git status

# 2. Tambahkan file yang diubah
git add .

# 3. Commit dengan pesan jelas
git commit -m "feat: add new feature"

# 4. Push ke GitHub
git push origin master
```

---

## 📚 Referensi / References

- [Git Documentation](https://git-scm.com/doc)
- [GitHub Docs](https://docs.github.com/)
- [Personal Access Tokens](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token)

---

## 💡 Tips

1. **Commit Message yang Baik**:
   - `feat:` untuk fitur baru
   - `fix:` untuk perbaikan bug
   - `docs:` untuk dokumentasi
   - `chore:` untuk maintenance

2. **Jangan Commit**:
   - File `.env` (berisi kredensial)
   - Folder `vendor/` (bisa diinstall ulang)
   - Folder `node_modules/` (bisa diinstall ulang)
   - File log (`*.log`)

3. **Branch Strategy**:
   - `master/main`: untuk production
   - `develop`: untuk development
   - `feature/xxx`: untuk fitur baru

---

**Selamat! Kode Anda sekarang sudah di GitHub! 🎉**

**Congratulations! Your code is now on GitHub! 🎉**

