# 📌 Task Breakdown & Checklist Progress
## Portal Terintegrasi UKM LeDHaK UNHAS

---

## 🎯 Status Keseluruhan Project (Overall Status)

- [x] **Milestone 1**: Inisialisasi Arsitektur Backend & Database
- [x] **Milestone 2**: Otentikasi Admin (Laravel Sanctum)
- [x] **Milestone 3**: Endpoint API Publik (Profil, Berita, Inventaris & Scan QR)
- [x] **Milestone 4**: Endpoint API Admin (CRUD Barang, Quick Status, Loan & Return, Log Audit)
- [x] **Milestone 5**: Sinkronisasi Otomatis Google Sheets (Google Apps Script)
- [x] **Milestone 6**: Dokumentasi & Panduan Integrasi Frontend (FE Guide & PRD)
- [ ] **Milestone 7**: Integrasi Frontend & Testing E2E

---

## 📑 Rincian Task Per Milestone

### 🔹 Milestone 1: Arsitektur Backend & Database (Selesai)
- [x] Inisialisasi Project Laravel 11 (`ledhak-integrated-portal`).
- [x] Buat skema migrasi database:
  - [x] `create_users_table`
  - [x] `create_items_table`
  - [x] `create_loan_records_table`
  - [x] `create_inventory_logs_table`
  - [x] `create_articles_table`
- [x] Buat DatabaseSeeder dengan **77 item inventaris asli** UKM LeDHaK UNHAS.
- [x] Buat akun pengurus default (`admin@ledhak-unhas.org`).

### 🔹 Milestone 2: Otentikasi Admin (Selesai)
- [x] Konfigurasi Laravel Sanctum untuk SPA/Mobile auth.
- [x] Buat `AuthController`:
  - [x] Endpoint `POST /api/auth/login` (Generate Sanctum Bearer Token).
  - [x] Endpoint `GET /api/auth/me` (Protected Profile).
  - [x] Endpoint `POST /api/auth/logout` (Revoke Token).

### 🔹 Milestone 3: API Endpoint Publik (Selesai)
- [x] `ProfileController`: Endpoint `GET /api/public/profile`.
- [x] `PublicArticleController`:
  - [x] Endpoint `GET /api/public/articles` (Paginated, Filter status `Published`).
  - [x] Endpoint `GET /api/public/articles/{slug}` (Detail artikel).
- [x] `PublicInventoryController`:
  - [x] Endpoint `GET /api/public/inventory` (Katalog 77 barang).
  - [x] Endpoint `GET /api/public/inventory/{item_code}` (Scan QR Code).

### 🔹 Milestone 4: API Endpoint Admin & Sirkulasi (Selesai)
- [x] `AdminItemController`:
  - [x] `GET /api/admin/items` (List item + loanRecords + inventoryLogs).
  - [x] `POST /api/admin/items` (Auto generate `item_code` & QR URL).
  - [x] `PUT /api/admin/items/{id}` & `DELETE /api/admin/items/{id}`.
  - [x] `PATCH /api/admin/items/{id}/status` (Quick Status Update).
- [x] `AdminLoanController`:
  - [x] `POST /api/admin/loans` (Transaksi Peminjaman & Validasi Status).
  - [x] `POST /api/admin/loans/{id}/return` (Pengembalian Barang).
- [x] `AdminArticleController`:
  - [x] CRUD Lengkap Berita (Termasuk Draft & Auto-Slug).

### 🔹 Milestone 5: Integrasi Google Sheets (Selesai)
- [x] Buat `GoogleSheetService` untuk mengirim payload HTTP POST ke Apps Script Web App.
- [x] Integrasi peminjaman baru ke Google Sheets.
- [x] Integrasi pengembalian barang ke Google Sheets.

### 🔹 Milestone 6: Dokumentasi & PRD (Selesai)
- [x] Buat `PRD.md` (Product Requirement Document).
- [x] Buat `API_DOCUMENTATION.md` (Spesifikasi teknis REST API).
- [x] Buat `FRONTEND_INTEGRATION_GUIDE.md` (Panduan integrasi tim FE + Axios snippet).

### 🔹 Milestone 7: Integrasi Frontend & Testing E2E (Next Action)
- [ ] Pengujian API menggunakan Postman / Bruno / Frontend App.
- [ ] Integrasi UI Frontend dengan REST API Backend.
- [ ] Testing E2E Peminjaman Barang -> Scan QR -> Google Sheets.
