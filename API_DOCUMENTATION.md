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
- **Dukungan CORS (Cross-Origin)**:
  Telah dikonfigurasi aktif untuk mengizinkan request dari aplikasi frontend (React / Vue / Vite / HTML) tanpa hambatan Cross-Origin.

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

### 🚪 Logout Admin
- **Endpoint**: `POST /api/auth/logout`
- **Akses**: Protected (`auth:sanctum`)

---

## 🌐 2. Endpoint Publik (`/public`)

### 🏢 Profile Organisasi
- **Endpoint**: `GET /api/public/profile`

### 📰 Daftar Berita / Artikel Publik
- **Endpoint**: `GET /api/public/articles`

### 🔍 Detail Berita (by Slug)
- **Endpoint**: `GET /api/public/articles/{slug}`

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

---

## 🛡️ 3. Endpoint Manajemen Admin (`/admin`)

### 📋 Daftar & Tambah Barang Inventaris
- **Endpoint**: `GET /api/admin/items` | `POST /api/admin/items`
- **Akses**: Protected (`auth:sanctum`)

### ⚡ Update Cepat Status Barang
- **Endpoint**: `PATCH /api/admin/items/{id}/status`
- **Akses**: Protected (`auth:sanctum`)
- **Request Body**:
  ```json
  {
    "status": "Perbaikan",
    "notes": "Sedang diperbaiki oleh pengurus divisi sekretariat"
  }
  ```
- **Response Success (200 OK)**:
  ```json
  {
    "message": "Status barang Spanduk lawan bicara baru berhasil diubah menjadi Perbaikan.",
    "data": {
      "id": 1,
      "status": "Perbaikan"
    }
  }
  ```

### 🤝 Konfirmasi Peminjaman Barang & Sync Google Sheets
- **Endpoint**: `POST /api/admin/loans`
- **Akses**: Protected (`auth:sanctum`)

### 🔄 Konfirmasi Pengembalian Barang
- **Endpoint**: `POST /api/admin/loans/{id}/return`
- **Akses**: Protected (`auth:sanctum`)

### 📝 Manajemen Artikel Organisasi (CRUD)
- **Endpoint**: `GET|POST|PUT|DELETE /api/admin/articles`
- **Akses**: Protected (`auth:sanctum`)

---

## ⚠️ Penanganan Error Standar (Error Handling)

- **401 Unauthorized**: Token Sanctum tidak dikirimkan atau tidak valid.
- **422 Validation Error**: Data request tidak memenuhi aturan validasi.
