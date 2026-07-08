# Walkthrough - Form Pengajuan Change Request Dahana

Saya telah menyelesaikan pembuatan aplikasi **Form Pengajuan Change Request** untuk PT Dahana (Persero) menggunakan Laravel 13, Tailwind CSS v4, SQLite, dan DomPDF untuk cetak/export PDF.

## Fitur Utama yang Diimplementasikan

1. **Master Template Form (Sisi Admin)**:
   - CRUD Template Form lengkap.
   - Manajemen Bagian (Section) dan Kolom (Field) secara dinamis.
   - Mendukung tipe data input: *Text, Textarea, Checkbox, Checkbox Group (Pilihan Ganda)*, dan *Tabel Dinamis* (contoh: Tabel Dampak Biaya/Waktu).
   - Penataan grid Approval (tanda tangan digital) dinamis di bagian bawah form.

2. **Pengisian Form (Sisi User/Public)**:
   - Portal halaman utama yang menampilkan daftar form aktif.
   - Halaman pengisian form dengan antarmuka premium, mirip perpaduan antara kertas cetak resmi dengan Google Forms.
   - Section 1 (Identitas Pemohon dan Peruntukan) dibuat statis sesuai dengan format wajib dari PT Dahana.
   - Validasi formulir dinamis sesuai aturan wajib isi (*required*) yang disetel oleh Admin di template.
   - Penomoran kode pengisian acak unik (misal: `CR-XXXXXXXXXX`) setelah pengiriman sukses.

3. **Cetak & Export PDF**:
   - Menghasilkan file PDF dengan format layout yang mereplikasi dokumen asli di gambar Anda.
   - Header PDF lengkap dengan area logo, judul, Author, Tanggal, Status, dan Revisi.
   - Layout tanda tangan diatur menggunakan struktur grid tabel yang solid untuk menghindari kerusakan render di PDF generator (DomPDF).

---

## Perubahan File & Struktur Code

### Database & Migrations
- [2026_07_08_080001_create_form_templates_table.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/database/migrations/2026_07_08_080001_create_form_templates_table.php) — Menyimpan metadata template.
- [2026_07_08_080002_create_template_sections_table.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/database/migrations/2026_07_08_080002_create_template_sections_table.php) — Menyimpan section formulir.
- [2026_07_08_080003_create_template_fields_table.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/database/migrations/2026_07_08_080003_create_template_fields_table.php) — Menyimpan field/kolom dinamis.
- [2026_07_08_080004_create_form_submissions_table.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/database/migrations/2026_07_08_080004_create_form_submissions_table.php) — Menyimpan identitas statis pengisian user.
- [2026_07_08_080005_create_submission_values_table.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/database/migrations/2026_07_08_080005_create_submission_values_table.php) — Menyimpan nilai isian dinamis user.
- [2026_07_08_080006_add_role_to_users_table.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/database/migrations/2026_07_08_080006_add_role_to_users_table.php) — Kolom peran untuk membedakan Admin.

### Models
- [User.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Models/User.php) — Update role fillable & method `isAdmin()`.
- [FormTemplate.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Models/FormTemplate.php) — Model template form.
- [TemplateSection.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Models/TemplateSection.php) — Model section form.
- [TemplateField.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Models/TemplateField.php) — Model field form.
- [FormSubmission.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Models/FormSubmission.php) — Model data pengajuan.
- [SubmissionValue.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Models/SubmissionValue.php) — Model nilai field.

### Seeders
- [AdminSeeder.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/database/seeders/AdminSeeder.php) — Seeder akun administrator default.
- [DefaultTemplateSeeder.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/database/seeders/DefaultTemplateSeeder.php) — Membuat struktur "Form Pengajuan Change Request Infrastructure" default lengkap sesuai foto coretan/gambar.

### Controllers & Middleware
- [AdminTemplateController.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Http/Controllers/AdminTemplateController.php) — Manajemen template & list submission.
- [FormController.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Http/Controllers/FormController.php) — Portal user, halaman input & simpan form.
- [PdfController.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Http/Controllers/PdfController.php) — Render HTML ke PDF via DomPDF.
- [AuthController.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Http/Controllers/AuthController.php) — Autentikasi admin.
- [AdminMiddleware.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/app/Http/Middleware/AdminMiddleware.php) — Memproteksi rute admin.

### Views & CSS Configurations
- [app.blade.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/resources/views/layouts/app.blade.php) & [admin.blade.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/resources/views/layouts/admin.blade.php) — Layout utama.
- [welcome.blade.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/resources/views/welcome.blade.php) — Portal utama.
- [fill.blade.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/resources/views/forms/fill.blade.php) — Formulir isian user.
- [success.blade.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/resources/views/forms/success.blade.php) — Notifikasi sukses & link PDF.
- [pdf.blade.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/resources/views/forms/pdf.blade.php) — Layout template PDF.
- [dashboard.blade.php](file:///d:/TELKOM/MAGANG/JOB%20MAGANG/form-dahana/resources/views/admin/dashboard.blade.php) — Dashboard admin.
- [php.ini](file:///E:/PHP/php.ini) — Diperbarui untuk mengaktifkan extension `sqlite3`, `pdo_sqlite`, dan `mbstring`.

---

## Cara Menjalankan & Menguji Aplikasi

### 1. Jalankan Aplikasi
Jalankan server Laravel dengan command:
```bash
php artisan serve
```

### 2. Akun Akses Admin
Untuk masuk ke Dashboard Admin, buka `/login` di browser Anda:
- **Email**: `admin@dahana.id`
- **Password**: `password`

### 3. Alur Pengujian Pengguna
1. Buka halaman utama `/` (Landing Page).
2. Anda akan melihat tombol **"Mulai Isi Formulir"** pada kartu *Form Pengajuan Change Request Infrastructure*.
3. Isi kolom **Identitas** (Nama, Jabatan, Departemen, dll).
4. Isi kolom dinamis seperti **Prioritas**, **Deskripsi Perubahan**, **Alasan Perubahan**, **Rencana Pemulihan**, **Dampak Perubahan**, dan tuliskan nama pemberi **Approval** di bagian bawah.
5. Klik **"Kirim Formulir Pengajuan"**.
6. Anda akan dialihkan ke halaman Sukses yang memuat kode acak pengajuan (contoh: `CR-AJFHDUDHAW`).
7. Klik **"Cetak / Download PDF"** untuk melihat dokumen PDF rapi hasil isian Anda.
8. Masuk ke panel admin via `/login` -> buka menu **"Data Pengajuan (User)"** untuk melihat, menghapus, atau mengunduh ulang PDF pengajuan tersebut.
