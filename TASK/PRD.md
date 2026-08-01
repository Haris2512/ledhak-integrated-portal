# 📋 Product Requirement Document (PRD)
## Portal Terintegrasi UKM LeDHaK UNHAS

---

## 📄 1. Ringkasan Produk (Product Summary)

| Item | Deskripsi |
| :--- | :--- |
| **Nama Produk** | Portal Terintegrasi UKM LeDHaK UNHAS |
| **Organisasi** | Lembaga Debat dan Hak Asasi Manusia Universitas Hasanuddin |
| **Tujuan Utama** | Mengintegrasikan portal publik (profil organisasi & berita) dan sistem manajemen inventaris sekretariat berbasis QR Code & sinkronisasi Google Sheets. |
| **Target User** | **Publik / Mahasiswa** (Melihat profil, berita, katalog inventaris, scan QR) & **Admin Pengurus** (Manajemen barang, peminjaman, artikel). |
| **Tech Stack** | Backend: Laravel 11 (REST API, Sanctum Auth) <br> Integration: Google Apps Script Web App (Google Sheets) <br> Frontend: Web Portal (React / Vue / Vite / Next.js) |

---

## 🎯 2. Visi & Tujuan (Vision & Objectives)

### 2.1 Visi
Menjadi sistem informasi terpadu yang modern, akuntabel, dan efisien dalam pengelolaan tata kelola sekretariat dan publikasi kegiatan UKM LeDHaK UNHAS.

### 2.2 Tujuan Utama
1. **Transparansi & Digitalisasi Inventaris**: Memudahkan pelacakan 77+ aset inventaris sekretariat melalui QR Code.
2. **Otomatisasi Log Peminjaman**: Mencatat peminjaman dan pengembalian barang secara otomatis yang tersinkronisasi langsung ke Google Sheets pengurus.
3. **Penyebaran Informasi Publik**: Menyediakan akses berita, artikel hukum/debat, serta profil resmi UKM LeDHaK UNHAS.

---

## 👥 3. Peran Pengguna (User Roles)

### 3.1 Public User (Mahasiswa / Masyarakat Umum)
- Membaca informasi profil, visi, misi, dan kontak UKM LeDHaK UNHAS.
- Membaca artikel dan berita resmi organisasi.
- Mengakses katalog inventaris sekretariat.
- Memindai (Scan) QR Code fisik barang untuk melihat detail spesifikasi dan ketersediaan barang secara real-time.

### 3.2 Admin Pengurus (Pengurus Sekretariat UKM)
- Login ke sistem menggunakan kredensial terautentikasi (Sanctum).
- Menambah, mengedit, memperbarui status, dan menghapus barang inventaris.
- Menggenerasi secara otomatis Kode Barang (`INV-LDK-XXX`) dan QR Code URL.
- Mencatat transaksi peminjaman dan pengembalian barang.
- Mengelola artikel organisasi (Draft / Published).
- Memantau log riwayat perubahan barang (*Audit Trail*).

---

## ⚡ 4. Kebutuhan Fungsional (Functional Requirements)

### FR-01: Public Organization Profile
- Sistem harus menyediakan API endpoint publik untuk menampilkan profil resmi UKM LeDHaK UNHAS (Visi, Misi, Deskripsi, Kontak).

### FR-02: Public Article Catalog
- Sistem harus dapat menampilkan berita/artikel dengan status `Published`.
- Sistem mendukung pencarian/detail berita berdasarkan `slug` yang ramah SEO.

### FR-03: Public Inventory Catalog & QR Code Resolution
- Sistem dapat menyajikan 77+ katalog barang inventaris dengan pagination.
- Setiap barang dapat diakses langsung via `item_code` melalui pemindaian QR Code.

### FR-04: Admin Authentication & Session Management
- Menggunakan otentikasi Sanctum Bearer Token.
- Endpoint `/auth/login`, `/auth/me`, dan `/auth/logout`.

### FR-05: Inventory Item Management
- Fitur CRUD Barang untuk admin.
- Auto-generate `item_code` unik jika tidak diisi secara manual.
- Auto-generate URL QR Code berukuran 300x300 pixel via API QR Code Server.
- Dukungan Quick Status Update (`Tersedia`, `Dipinjam`, `Perbaikan`).

### FR-06: Loan & Return Circulation System
- Admin dapat mencatat peminjaman barang. Validasi: Barang harus berstatus `Tersedia`.
- Setelah peminjaman dicatat, status barang otomatis berubah menjadi `Dipinjam`.
- Admin dapat mengonfirmasi pengembalian barang. Status barang otomatis kembali menjadi `Tersedia`.
- Setiap transaksi mencatat log audit pada tabel `inventory_logs`.

### FR-07: Real-time Google Sheets Synchronization
- Setiap pencatatan peminjaman dan konfirmasi pengembalian otomatis mengirimkan data secara asynchronous ke Google Sheets via Google Apps Script Web App.

---

## 🛡️ 5. Kebutuhan Non-Fungsional (Non-Functional Requirements)

1. **Performa**: Response time API rata-rata < 200 ms untuk query standar.
2. **Keamanan**:
   - Proteksi route admin menggunakan Sanctum Middleware (`auth:sanctum`).
   - Validasi input ketat di seluruh FormRequest (`StoreItemRequest`, `StoreLoanRequest`, `StoreArticleRequest`).
   - Menggunakan Password Hashing (Bcrypt/Argon2).
3. **Cross-Origin Resource Sharing (CORS)**: Konfigurasi CORS aktif mengizinkan integrasi dari domain frontend manapun.
4. **Data Integrity**: Menggunakan Database Transactions (`DB::transaction`) untuk memastikan konsistensi data peminjaman dan log.

---

## 🗄️ 6. Arsitektur Data & Model Database

1. **`users`**: Data admin pengurus (`id`, `name`, `email`, `password`, `created_at`, `updated_at`).
2. **`items`**: Data barang inventaris (`id`, `item_code`, `name`, `category`, `description`, `status`, `qr_code_url`, `photo_path`).
3. **`loan_records`**: Data transaksi peminjaman (`id`, `item_id`, `borrower_name`, `borrower_phone`, `loan_date`, `return_date`, `status`).
4. **`inventory_logs`**: Log histori perubahan barang (`id`, `item_id`, `user_id`, `action`, `notes`).
5. **`articles`**: Data artikel & berita (`id`, `title`, `slug`, `content`, `image_path`, `status`).
