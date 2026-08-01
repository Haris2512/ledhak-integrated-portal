# 📖 API Documentation & Frontend Integration Guide
> **UKM LeDHaK UNHAS Integrated Portal Backend**  
> *Dokumentasi REST API lengkap dan panduan integrasi untuk Tim Frontend (FE).*

---

## 📌 1. Informasi Umum & Quick Start

| Parameter | Nilai |
| :--- | :--- |
| **Base URL Lokal** | `http://localhost:8000/api` |
| **Default Format** | `application/json` |
| **Authentication** | Laravel Sanctum (`Bearer Token`) |
| **CORS** | Active (`*` / All Origins Allowed) |

### 🔑 Credentials Admin Default (Development/Testing)
* **Email**: `admin@ledhak-unhas.org`
* **Password**: `password123`

### 📋 Header Wajib Setiap Request
```http
Accept: application/json
Content-Type: application/json
```

Untuk endpoint terlindungi (`/admin/*`, `/auth/me`, `/auth/logout`), sertakan token:
```http
Authorization: Bearer <access_token>
```

---

## 🔐 2. Autentikasi Admin (`/api/auth`)

### 🔑 2.1 Login Admin
* **Endpoint**: `POST /api/auth/login`
* **Akses**: Public
* **Request Body**:
  ```json
  {
    "email": "admin@ledhak-unhas.org",
    "password": "password123"
  }
  ```
* **Response Success (200 OK)**:
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

---

## 🌐 3. Endpoint Publik (`/api/public`) - Open Access

* `GET /api/public/profile` -> Info profil UKM LeDHaK UNHAS
* `GET /api/public/articles` -> Katalog berita/artikel publik (Published)
* `GET /api/public/articles/{slug}` -> Detail berita by slug
* `GET /api/public/inventory` -> Katalog 77 barang inventaris
* `GET /api/public/inventory/{item_code}` -> Detail barang / Scan QR Code

---

## 🛡️ 4. Endpoint Manajemen Admin (`/api/admin`) - Protected

* `GET /api/admin/items` | `POST /api/admin/items` | `PUT /api/admin/items/{id}` | `DELETE /api/admin/items/{id}`
* `PATCH /api/admin/items/{id}/status` -> Quick status update (`Tersedia`, `Dipinjam`, `Perbaikan`)
* `POST /api/admin/loans` -> Catat peminjaman & sync Google Sheets
* `POST /api/admin/loans/{id}/return` -> Konfirmasi pengembalian & sync Google Sheets
* `GET|POST|PUT|DELETE /api/admin/articles` -> CRUD Artikel
