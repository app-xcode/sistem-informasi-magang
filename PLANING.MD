# PLAN IMPLEMENTASI CODEX

## Sistem Informasi Magang — Laravel 13 + Blade + Tailwind CSS

### 1. KONTEKS PROJECT

Project berada di:

`C:\xampp\htdocs\sistem-magang`

Stack utama:

* Laravel 13
* PHP 8.3+
* MySQL
* Blade
* Tailwind CSS
* Laravel Authentication
* Role Middleware

Authentication sudah berhasil.

Role yang digunakan:

* `admin`
* `mahasiswa`
* `dosen`

Route dashboard yang sudah tersedia:

* `/admin/dashboard`
* `/mahasiswa/dashboard`
* `/dosen/dashboard`

Jangan mengubah mekanisme authentication dan role middleware yang sudah berfungsi kecuali memang diperlukan.

---

# 2. TUJUAN UTAMA

Mulai bangun UI aplikasi Sistem Informasi Magang menggunakan:

* Tailwind CSS
* Blade Components
* Layout Blade
* Responsive Design
* Sidebar
* Topbar
* Dashboard Cards
* Tables
* Forms
* Modal jika diperlukan
* Flash message
* Validation message

Dashboard tidak boleh hanya berupa tampilan statis.

Setiap modul yang dibuat harus mulai terhubung dengan database, model, controller, validation, route, dan CRUD sesuai kebutuhan.

---

# 3. STRUKTUR UI

Buat layout utama:

`resources/views/layouts/app.blade.php`

Buat komponen reusable:

`resources/views/components/`

Contoh:

* sidebar
* topbar
* alert
* card
* button
* modal
* table
* badge

Gunakan layout yang konsisten untuk semua role.

Struktur:

```text
resources/views/
├── layouts/
│   └── app.blade.php
│
├── components/
│   ├── sidebar.blade.php
│   ├── topbar.blade.php
│   ├── alert.blade.php
│   ├── card.blade.php
│   └── badge.blade.php
│
├── auth/
│   └── login.blade.php
│
├── admin/
│   ├── dashboard.blade.php
│   └── ...
│
├── mahasiswa/
│   ├── dashboard.blade.php
│   └── ...
│
└── dosen/
    ├── dashboard.blade.php
    └── ...
```

---

# 4. DESAIN UMUM

Gunakan desain dashboard modern, bersih, profesional, dan responsive.

Desktop:

```text
┌─────────────────────────────────────────────┐
│ Sidebar │ Topbar                            │
│         ├───────────────────────────────────┤
│         │                                   │
│ Menu    │       Content                     │
│         │                                   │
│         │                                   │
└─────────┴───────────────────────────────────┘
```

Mobile:

* Sidebar berubah menjadi drawer
* Topbar tetap mudah digunakan
* Semua tabel harus responsive
* Form harus nyaman digunakan di layar kecil

Gunakan Tailwind utility classes.

Jangan membuat CSS panjang jika dapat dilakukan dengan Tailwind.

---

# 5. SIDEBAR BERDASARKAN ROLE

Sidebar harus dinamis berdasarkan:

```php
auth()->user()->role
```

## ADMIN

Menu:

* Dashboard
* Data Mahasiswa
* Data Dosen
* Data Perusahaan
* Pengajuan Magang
* Data Magang
* Monitoring Magang
* Laporan
* Pengaturan

## MAHASISWA

Menu:

* Dashboard
* Profil
* Pengajuan Magang
* Tempat Magang
* Status Magang
* Logbook
* Laporan Magang

## DOSEN

Menu:

* Dashboard
* Profil
* Mahasiswa Bimbingan
* Pengajuan Magang
* Monitoring Magang
* Logbook
* Penilaian
* Laporan

Menu yang tampil harus sesuai role.

---

# 6. DASHBOARD ADMIN

Buat dashboard admin yang menampilkan ringkasan sistem.

Card:

* Total Mahasiswa
* Total Dosen
* Total Perusahaan
* Pengajuan Menunggu
* Mahasiswa Sedang Magang
* Magang Selesai

Tambahkan:

### Pengajuan terbaru

Table:

* Mahasiswa
* Perusahaan
* Tanggal Pengajuan
* Status
* Action

### Aktivitas terbaru

Tampilkan aktivitas sistem terbaru jika data tersedia.

Jangan menggunakan angka dummy jika database sudah memiliki data.

Gunakan query database melalui controller.

---

# 7. DASHBOARD MAHASISWA

Dashboard mahasiswa menampilkan:

### Informasi mahasiswa

* Nama
* NIM
* Program Studi
* Status

### Status magang

Contoh status:

* Belum Mengajukan
* Menunggu Persetujuan
* Disetujui
* Sedang Magang
* Selesai

### Ringkasan

* Perusahaan
* Dosen Pembimbing
* Tanggal Mulai
* Tanggal Selesai

### Logbook

Tampilkan:

* Jumlah logbook
* Logbook terakhir
* Status validasi

### Quick Action

* Ajukan Magang
* Tambah Logbook
* Lihat Status
* Upload Laporan

Jika data belum tersedia, tampilkan empty state yang baik, bukan dummy data.

---

# 8. DASHBOARD DOSEN

Dashboard dosen menampilkan:

### Ringkasan

* Mahasiswa Bimbingan
* Pengajuan Baru
* Sedang Magang
* Logbook Menunggu Validasi
* Laporan Menunggu Penilaian

### Mahasiswa Bimbingan

Table:

* Mahasiswa
* NIM
* Perusahaan
* Status Magang
* Progress
* Action

### Logbook terbaru

Tampilkan logbook mahasiswa yang membutuhkan perhatian.

---

# 9. MODUL ADMIN

Setelah dashboard selesai, implementasikan modul secara bertahap.

Urutan:

### A. Data Mahasiswa

CRUD:

* List
* Detail
* Tambah
* Edit
* Hapus

Data minimal:

* Nama
* NIM
* Email
* Program Studi
* Nomor HP
* Alamat
* Status

### B. Data Dosen

CRUD:

* Nama
* NIDN
* Email
* Nomor HP
* Program Studi

### C. Data Perusahaan

CRUD:

* Nama perusahaan
* Alamat
* Kota
* Provinsi
* Kontak
* Email
* Website
* Deskripsi

### D. Pengajuan Magang

Admin dapat:

* melihat pengajuan
* melihat detail
* memproses pengajuan
* mengubah status

---

# 10. MODUL MAHASISWA

Mahasiswa dapat:

### Profil

* Melihat profil
* Mengedit profil

### Pengajuan Magang

Mahasiswa dapat:

* melihat daftar pengajuan
* membuat pengajuan
* memilih perusahaan
* menentukan periode
* upload dokumen jika diperlukan
* melihat status

### Logbook

Mahasiswa dapat:

* membuat logbook
* melihat logbook
* mengedit logbook jika belum divalidasi
* melihat status validasi

Data logbook minimal:

* tanggal
* kegiatan
* hasil
* kendala
* dokumentasi
* status validasi

### Laporan Magang

Mahasiswa dapat:

* upload laporan
* melihat status laporan
* memperbaiki laporan jika dikembalikan

---

# 11. MODUL DOSEN

Dosen dapat:

### Mahasiswa Bimbingan

* melihat mahasiswa bimbingan
* melihat detail mahasiswa
* melihat perusahaan
* melihat periode magang

### Monitoring

* melihat progress mahasiswa
* melihat logbook

### Validasi Logbook

Dosen dapat:

* melihat logbook
* menyetujui
* menolak
* memberikan catatan

### Penilaian

Buat halaman penilaian mahasiswa berdasarkan rancangan database yang tersedia.

---

# 12. DATABASE-FIRST

Sebelum membuat CRUD baru:

1. Periksa migration yang sudah ada.
2. Periksa model.
3. Periksa relationship.
4. Periksa nama tabel dan kolom.
5. Jangan membuat migration duplikat.
6. Jangan mengubah struktur database yang sudah benar tanpa alasan.
7. Jika struktur database belum memiliki data yang diperlukan, buat migration baru.

Gunakan:

```bash
php artisan migrate:status
```

dan periksa migration/model yang sudah tersedia.

---

# 13. CONTROLLER

Gunakan controller terpisah berdasarkan modul.

Contoh:

```text
app/Http/Controllers/
├── AuthController.php
├── Admin/
│   ├── DashboardController.php
│   ├── MahasiswaController.php
│   ├── DosenController.php
│   ├── PerusahaanController.php
│   └── PengajuanController.php
│
├── Mahasiswa/
│   ├── DashboardController.php
│   ├── ProfileController.php
│   ├── PengajuanController.php
│   ├── LogbookController.php
│   └── LaporanController.php
│
└── Dosen/
    ├── DashboardController.php
    ├── ProfileController.php
    ├── MahasiswaController.php
    ├── LogbookController.php
    └── PenilaianController.php
```

Gunakan resource controller jika sesuai.

---

# 14. ROUTE

Gunakan grouping berdasarkan role:

```php
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(...);

Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(...);

Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(...);
```

Jangan membuat route yang dapat diakses role lain.

---

# 15. VALIDATION

Gunakan Laravel Form Request jika validasi sudah cukup kompleks.

Contoh:

```text
app/Http/Requests/
├── StoreMahasiswaRequest.php
├── UpdateMahasiswaRequest.php
├── StorePerusahaanRequest.php
├── StorePengajuanRequest.php
└── StoreLogbookRequest.php
```

Semua form harus memiliki:

* validation
* error message
* old input
* success message

---

# 16. STATUS

Gunakan status yang konsisten.

Untuk pengajuan:

```text
pending
approved
rejected
```

Untuk proses magang:

```text
belum_mulai
berlangsung
selesai
```

Untuk logbook:

```text
pending
approved
rejected
```

Jangan membuat variasi nama status berbeda-beda di setiap modul.

Sesuaikan dengan enum/kolom database yang sebenarnya jika sudah tersedia.

---

# 17. KEAMANAN

Pastikan:

* Semua dashboard menggunakan `auth`
* Semua modul menggunakan role middleware
* User tidak dapat mengakses data role lain
* Gunakan authorization/policy jika diperlukan
* Gunakan CSRF
* Validasi semua input
* Jangan percaya `user_id` dari request jika dapat diambil dari authenticated user
* Gunakan `auth()->id()`

---

# 18. TAHAP PENGERJAAN CODEX

Jangan mengerjakan seluruh aplikasi sekaligus.

Kerjakan dalam milestone berikut:

## MILESTONE 1 — UI FOUNDATION

Buat:

* Tailwind
* Layout
* Sidebar
* Topbar
* Responsive navigation
* Alert
* Card
* Badge
* Button
* Dashboard layout

Selesaikan dan test terlebih dahulu.

## MILESTONE 2 — DASHBOARD

Buat:

* Admin Dashboard
* Mahasiswa Dashboard
* Dosen Dashboard

Hubungkan dengan database.

## MILESTONE 3 — ADMIN

Implementasikan:

1. Mahasiswa
2. Dosen
3. Perusahaan
4. Pengajuan
5. Data Magang

## MILESTONE 4 — MAHASISWA

Implementasikan:

1. Profil
2. Pengajuan Magang
3. Status Magang
4. Logbook
5. Laporan

## MILESTONE 5 — DOSEN

Implementasikan:

1. Profil
2. Mahasiswa Bimbingan
3. Monitoring
4. Validasi Logbook
5. Penilaian

## MILESTONE 6 — POLISH

* Responsive
* Empty state
* Loading state
* Error state
* Flash notification
* Confirmation modal
* Pagination
* Search
* Filter
* UI consistency

---

# 19. ATURAN PENTING CODEX

Sebelum mengubah kode:

1. Inspect project terlebih dahulu.
2. Jangan mengasumsikan nama tabel/kolom.
3. Jangan menghapus fitur yang sudah bekerja.
4. Jangan mengganti authentication yang sudah berhasil.
5. Jangan membuat data dummy untuk menggantikan database.
6. Gunakan data database sebenarnya.
7. Pertahankan role middleware.
8. Gunakan Laravel 13 conventions.
9. Gunakan Blade + Tailwind.
10. Hindari dependency tambahan jika tidak diperlukan.
11. Setelah setiap milestone, jalankan test/build yang relevan.
12. Perbaiki error sebelum lanjut ke milestone berikutnya.

---

# 20. LANGKAH PERTAMA YANG HARUS DIKERJAKAN

Mulai sekarang hanya kerjakan:

### MILESTONE 1 — UI FOUNDATION

Inspect terlebih dahulu:

```text
composer.json
package.json
routes/web.php
bootstrap/app.php
app/Models/
app/Http/Controllers/
app/Http/Middleware/
resources/views/
resources/css/
resources/js/
database/migrations/
```

Kemudian:

1. Pastikan Tailwind CSS sudah terpasang dan bekerja.
2. Buat layout utama.
3. Buat sidebar responsive.
4. Buat topbar.
5. Buat komponen UI reusable.
6. Integrasikan dashboard admin.
7. Integrasikan dashboard mahasiswa.
8. Integrasikan dashboard dosen.
9. Pastikan role masing-masing tetap terlindungi.
10. Jangan lanjut membuat CRUD sebelum UI foundation selesai dan dapat dijalankan.

Setelah selesai, tampilkan ringkasan file yang dibuat/diubah dan hasil pengujian.
