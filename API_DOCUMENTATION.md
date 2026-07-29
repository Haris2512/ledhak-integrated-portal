# 📖 API Documentation - Portal Terintegrasi UKM LeDHaK UNHAS

Dokumentasi REST API ini dirancang untuk memudahkan pengintegrasian antara **Frontend (Public & Admin View)** dengan **Backend Laravel**.

---

## 🌐 Base URL & Konfigurasi Umum

- **Base URL (Lokal)**: `http://localhost:8000/api`
- **Header Standar**:
  ```http
  Accept: application/json
  Content-Type: application/json
  ```
- **Otentikasi Admin (Sanctum)**:
  Untuk endpoint terlindungi (`/admin/*` & `/auth/logout`, `/auth/me`), sertakan header:
  ```http
  Authorization: Bearer <access_token>
  ```

---

## 🔐 1. Otentikasi Admin (`/auth`)

### 🔑 Login Admin
- **Endpoint**: `POST /api/auth/login`
- **Akses**: Public (Restricted Admin Credentials)
- **Request Body**:
  ```json
  {
    "email": "admin@ledhak-unhas.org",
    "password": "password123"
  }
  ```
- **Response Success (200 OK)**:
  ```json
  {
    "message": "Login berhasil.",
    "access_token": "1|qlx89A...sampletoken",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "Admin Pengurus LeDHaK",
      "email": "admin@ledhak-unhas.org"
    }
  }
  ```

### 👤 Profile Admin Aktif
- **Endpoint**: `GET /api/auth/me`
- **Akses**: Protected (`auth:sanctum`)
- **Response Success (200 OK)**:
  ```json
  {
    "user": {
      "id": 1,
      "name": "Admin Pengurus LeDHaK",
      "email": "admin@ledhak-unhas.org"
    }
  }
  ```

### 🚪 Logout Admin
- **Endpoint**: `POST /api/auth/logout`
- **Akses**: Protected (`auth:sanctum`)
- **Response Success (200 OK)**:
  ```json
  {
    "message": "Logout berhasil."
  }
  ```

---

## 🌐 2. Endpoint Publik (`/public`)

### 🏢 Profile Organisasi
- **Endpoint**: `GET /api/public/profile`
- **Response Success (200 OK)**:
  ```json
  {
    "organization_name": "UKM LeDHaK UNHAS",
    "full_name": "Lembaga Debat dan Hak Asasi Manusia Universitas Hasanuddin",
    "description": "Unit Kegiatan Mahasiswa...",
    "vision": "Menjadi wadah pengembangan...",
    "mission": [
      "Meningkatkan budaya kritis...",
      "Menyelenggarakan kajian...",
      "Memfasilitasi tata kelola..."
    ],
    "contact": {
      "email": "ledhak@unhas.org",
      "whatsapp": "6281234567890",
      "location": "Sekretariat UKM LeDHaK UNHAS"
    }
  }
  ```

### 📰 Daftar Berita / Artikel Publik
- **Endpoint**: `GET /api/public/articles`
- **Response Success (200 OK)**:
  ```json
  {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Open Recruitment Anggota Baru UKM LeDHaK UNHAS 2026",
        "slug": "open-recruitment-penerimaan-anggota-baru-ledhak-unhas-2026",
        "content": "UKM LeDHaK UNHAS secara resmi membuka pendaftaran...",
        "image_path": "articles/oprec-2026.jpg",
        "status": "Published",
        "created_at": "2026-07-29T10:00:00.000000Z"
      }
    ]
  }
  ```

### 🔍 Detail Berita (by Slug)
- **Endpoint**: `GET /api/public/articles/{slug}`
- **Response Success (200 OK)**:
  ```json
  {
    "data": {
      "id": 1,
      "title": "Open Recruitment Anggota Baru UKM LeDHaK UNHAS 2026",
      "slug": "open-recruitment-penerimaan-anggota-baru-ledhak-unhas-2026",
      "content": "Isi berita lengkap...",
      "image_path": "articles/oprec-2026.jpg",
      "status": "Published"
    }
  }
  ```

### 📦 Katalog Inventaris Publik (77 Items)
- **Endpoint**: `GET /api/public/inventory`
- **Response Success (200 OK)**:
  ```json
  {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "item_code": "INV-LDK-001",
        "name": "Spanduk lawan bicara baru",
        "category": "Spanduk & Banner",
        "description": "Jumlah ketersediaan: 7 Pcs. Perlengkapan resmi UKM LeDHaK UNHAS.",
        "status": "Tersedia",
        "qr_code_url": "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=..."
      }
    ]
  }
  ```

### 📱 Scan QR Code / Detail Barang (by item_code)
- **Endpoint**: `GET /api/public/inventory/{item_code}`
- **Response Success (200 OK)**:
  ```json
  {
    "data": {
      "id": 1,
      "item_code": "INV-LDK-001",
      "name": "Spanduk lawan bicara baru",
      "category": "Spanduk & Banner",
      "status": "Tersedia"
    }
  }
  ```

---

## 🛡️ 3. Endpoint Manajemen Admin (`/admin`)

### 📋 Daftar & Tambah Barang Inventaris
- **Endpoint**: `GET /api/admin/items` | `POST /api/admin/items`
- **Akses**: Protected (`auth:sanctum`)
- **Request Body (POST)**:
  ```json
  {
    "name": "Sound System Portable",
    "category": "Elektronik & Sound",
    "description": "Sound system outdoor",
    "status": "Tersedia"
  }
  ```

### 🤝 Konfirmasi Peminjaman Barang & Sync Google Sheets
- **Endpoint**: `POST /api/admin/loans`
- **Akses**: Protected (`auth:sanctum`)
- **Request Body**:
  ```json
  {
    "item_id": 1,
    "borrower_name": "Ahmad Ramadhan",
    "borrower_phone": "081234567890",
    "loan_date": "2026-07-29",
    "return_date": "2026-08-01"
  }
  ```
- **Response Success (201 Created)**:
  ```json
  {
    "message": "Peminjaman berhasil dicatat, status barang diubah menjadi Dipinjam, dan log disinkronkan.",
    "data": {
      "id": 1,
      "item_id": 1,
      "borrower_name": "Ahmad Ramadhan",
      "borrower_phone": "081234567890",
      "status": "Active"
    }
  }
  ```

### 🔄 Konfirmasi Pengembalian Barang
- **Endpoint**: `POST /api/admin/loans/{id}/return`
- **Akses**: Protected (`auth:sanctum`)
- **Response Success (200 OK)**:
  ```json
  {
    "message": "Pengembalian barang Spanduk lawan bicara baru berhasil dikonfirmasi.",
    "data": {
      "id": 1,
      "item_id": 1,
      "status": "Returned",
      "return_date": "2026-07-29",
      "item": {
        "id": 1,
        "item_code": "INV-LDK-001",
        "name": "Spanduk lawan bicara baru",
        "status": "Tersedia"
      }
    }
  }
  ```

### 📝 Manajemen Artikel Organisasi (CRUD)
- **Endpoint**: `GET|POST|PUT|DELETE /api/admin/articles`
- **Akses**: Protected (`auth:sanctum`)

---

## ⚠️ Penanganan Error Standar (Error Handling)

- **401 Unauthorized**: Token Sanctum tidak dikirimkan atau tidak valid.
  ```json
  {
    "message": "Unauthenticated."
  }
  ```
- **422 Validation Error**: Data request tidak memenuhi aturan validasi.
  ```json
  {
    "message": "The item_id field is required.",
    "errors": {
      "item_id": ["The item_id field is required."]
    }
  }
  ```
