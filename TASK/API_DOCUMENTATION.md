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
* **Response Error (422 Unprocessable Content)**:
  ```json
  {
    "message": "Kredensial yang diberikan tidak cocok dengan data kami.",
    "errors": {
      "email": [
        "Kredensial yang diberikan tidak cocok dengan data kami."
      ]
    }
  }
  ```

### 👤 2.2 Profile Admin Aktif (Me)
* **Endpoint**: `GET /api/auth/me`
* **Akses**: Protected (`auth:sanctum`)
* **Response Success (200 OK)**:
  ```json
  {
    "user": {
      "id": 1,
      "name": "Admin Pengurus LeDHaK",
      "email": "admin@ledhak-unhas.org",
      "created_at": "2026-08-01T11:00:00.000000Z",
      "updated_at": "2026-08-01T11:00:00.000000Z"
    }
  }
  ```

### 🚪 2.3 Logout Admin
* **Endpoint**: `POST /api/auth/logout`
* **Akses**: Protected (`auth:sanctum`)
* **Response Success (200 OK)**:
  ```json
  {
    "message": "Logout berhasil."
  }
  ```

---

## 🌐 3. Endpoint Publik (`/api/public`) - Open Access

### 🏢 3.1 Profile Organisasi UKM LeDHaK
* **Endpoint**: `GET /api/public/profile`
* **Akses**: Public
* **Response Success (200 OK)**:
  ```json
  {
    "organization_name": "UKM LeDHaK UNHAS",
    "full_name": "Lembaga Debat dan Hak Asasi Manusia Universitas Hasanuddin",
    "description": "Unit Kegiatan Mahasiswa di Universitas Hasanuddin yang berfokus pada pengembangan kemampuan penalaran, analisis hukum, debat ilmiah, dan pengkajian isu-isu Hak Asasi Manusia.",
    "vision": "Menjadi wadah pengembangan kapasitas intelektual dan kepemimpinan mahasiswa Universitas Hasanuddin dalam bidang penalaran, debat, dan advokasi HAM yang berintegritas.",
    "mission": [
      "Meningkatkan budaya kritis dan daya nalar mahasiswa melalui kegiatan debat ilmiah.",
      "Menyelenggarakan kajian dan edukasi rutin terkait isu-isu Hak Asasi Manusia.",
      "Memfasilitasi tata kelola inventaris dan sekretariat yang transparan, modern, dan akuntabel."
    ],
    "contact": {
      "email": "ledhak@unhas.ac.id",
      "whatsapp": "6281234567890",
      "location": "Sekretariat UKM LeDHaK, Gedung PKM Unhas Tamalanrea, Makassar"
    }
  }
  ```

### 📰 3.2 Katalog Berita & Artikel Publik
* **Endpoint**: `GET /api/public/articles?page=1`
* **Akses**: Public
* **Description**: Menampilkan daftar berita/artikel dengan status `Published`.
* **Response Success (200 OK)**:
  ```json
  {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Open Recruitment Penerimaan Anggota Baru UKM LeDHaK UNHAS 2026",
        "slug": "penerimaan-anggota-baru-ledhak-unhas-2026",
        "content": "UKM LeDHaK UNHAS secara resmi membuka pendaftaran...",
        "image_path": "articles/oprec-2026.jpg",
        "status": "Published",
        "created_at": "2026-08-01T11:00:00.000000Z"
      }
    ],
    "last_page": 1,
    "total": 1
  }
  ```

### 🔍 3.3 Detail Berita (by Slug)
* **Endpoint**: `GET /api/public/articles/{slug}`
* **Akses**: Public
* **Example**: `/api/public/articles/penerimaan-anggota-baru-ledhak-unhas-2026`
* **Response Success (200 OK)**:
  ```json
  {
    "data": {
      "id": 1,
      "title": "Open Recruitment Penerimaan Anggota Baru UKM LeDHaK UNHAS 2026",
      "slug": "penerimaan-anggota-baru-ledhak-unhas-2026",
      "content": "UKM LeDHaK UNHAS secara resmi membuka...",
      "image_path": "articles/oprec-2026.jpg",
      "status": "Published"
    }
  }
  ```

### 📦 3.4 Katalog Inventaris Publik (77 Items)
* **Endpoint**: `GET /api/public/inventory?page=1`
* **Akses**: Public
* **Response Success (200 OK)**:
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
        "qr_code_url": "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=http%3A%2F%2Flocalhost%3A8000%2Fapi%2Fpublic%2Finventory%2FINV-LDK-001",
        "photo_path": "items/item-001.jpg"
      }
    ],
    "last_page": 6,
    "total": 77
  }
  ```

### 📱 3.5 Scan QR Code / Detail Barang (by item_code)
* **Endpoint**: `GET /api/public/inventory/{item_code}`
* **Akses**: Public
* **Example**: `/api/public/inventory/INV-LDK-001`
* **Response Success (200 OK)**:
  ```json
  {
    "data": {
      "id": 1,
      "item_code": "INV-LDK-001",
      "name": "Spanduk lawan bicara baru",
      "category": "Spanduk & Banner",
      "description": "Jumlah ketersediaan: 7 Pcs. Perlengkapan resmi UKM LeDHaK UNHAS.",
      "status": "Tersedia",
      "qr_code_url": "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=..."
    }
  }
  ```

---

## 🛡️ 4. Endpoint Manajemen Admin (`/api/admin`) - Protected

### 📦 4.1 Manajemen Barang Inventaris

#### List Barang Inventaris
* **Endpoint**: `GET /api/admin/items?page=1`
* **Headers**: `Authorization: Bearer <access_token>`
* **Response**: Mengembalikan list barang lengkap beserta relasi `loanRecords` dan `inventoryLogs`.

#### Tambah Barang Inventaris Baru
* **Endpoint**: `POST /api/admin/items`
* **Headers**: `Authorization: Bearer <access_token>`
* **Request Body**:
  ```json
  {
    "item_code": "INV-LDK-CUSTOM", // (Optional: Jika dikosongkan, backend auto-generate otomatis)
    "name": "Proyektor EPSON EB-X400",
    "category": "Elektronik & Sound",
    "description": "Proyektor sekretariat 3300 lumens",
    "status": "Tersedia", // Optional: Default 'Tersedia' ('Tersedia'|'Dipinjam'|'Perbaikan')
    "photo_path": "items/proyektor.jpg" // Optional
  }
  ```

#### Detail Barang Inventaris
* **Endpoint**: `GET /api/admin/items/{id}`
* **Headers**: `Authorization: Bearer <access_token>`

#### Update Barang Inventaris
* **Endpoint**: `PUT /api/admin/items/{id}`
* **Headers**: `Authorization: Bearer <access_token>`
* **Request Body**:
  ```json
  {
    "name": "Proyektor EPSON EB-X400 Updated",
    "category": "Elektronik & Sound",
    "description": "Kondisi sangat baik",
    "status": "Tersedia"
  }
  ```

#### Quick Update Status Barang
* **Endpoint**: `PATCH /api/admin/items/{id}/status`
* **Headers**: `Authorization: Bearer <access_token>`
* **Request Body**:
  ```json
  {
    "status": "Perbaikan", // Opsi: "Tersedia" | "Dipinjam" | "Perbaikan"
    "notes": "Lensa proyektor sedang dalam perbaikan di toko servis"
  }
  ```

#### Hapus Barang Inventaris
* **Endpoint**: `DELETE /api/admin/items/{id}`
* **Headers**: `Authorization: Bearer <access_token>`

---

### 🤝 4.2 Catat Peminjaman & Pengembalian Barang

#### Catat Peminjaman Barang Baru (Auto Sync Google Sheets)
* **Endpoint**: `POST /api/admin/loans`
* **Headers**: `Authorization: Bearer <access_token>`
* **Rules**: Barang harus berstatus `Tersedia`. Setelah request sukses, status barang otomatis berubah menjadi `Dipinjam`, tercatat di inventory log, dan otomatis dikirim ke Google Sheets.
* **Request Body**:
  ```json
  {
    "item_id": 1,
    "borrower_name": "Ahmad Fauzi",
    "borrower_phone": "081234567890",
    "loan_date": "2026-08-01",
    "return_date": "2026-08-05" // Optional
  }
  ```
* **Response Success (201 Created)**:
  ```json
  {
    "message": "Peminjaman berhasil dicatat, status barang diubah menjadi Dipinjam, dan log disinkronkan.",
    "data": {
      "id": 1,
      "item_id": 1,
      "borrower_name": "Ahmad Fauzi",
      "borrower_phone": "081234567890",
      "loan_date": "2026-08-01",
      "return_date": "2026-08-05",
      "status": "Active"
    }
  }
  ```

#### Konfirmasi Pengembalian Barang (Auto Sync Google Sheets)
* **Endpoint**: `POST /api/admin/loans/{loan_id}/return`
* **Headers**: `Authorization: Bearer <access_token>`
* **Rules**: Status peminjaman berubah menjadi `Returned`, status barang kembali `Tersedia`, dan log pengembalian disinkronkan ke Google Sheets.
* **Response Success (200 OK)**:
  ```json
  {
    "message": "Pengembalian barang Spanduk lawan bicara baru berhasil dikonfirmasi.",
    "data": {
      "id": 1,
      "status": "Returned",
      "return_date": "2026-08-01"
    }
  }
  ```

---

### 📰 4.3 Manajemen Artikel / Berita Organisasi

* `GET /api/admin/articles` -> List semua artikel (Termasuk Draft)
* `POST /api/admin/articles` -> Buat artikel baru
  * Request body: `{"title": "...", "content": "...", "status": "Draft" | "Published"}`
* `GET /api/admin/articles/{id}` -> Detail artikel
* `PUT /api/admin/articles/{id}` -> Edit artikel
* `DELETE /api/admin/articles/{id}` -> Hapus artikel

---

## 💻 5. Contoh Setup Kode Frontend (Axios Interceptor)

Berikut adalah contoh konfigurasinya jika tim Frontend menggunakan **Axios** (React / Vue / Next.js / Vanilla JS):

```javascript
import axios from 'axios';

// 1. Inisialisasi Axios Instance
const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// 2. Interceptor Request untuk Otomatis Mengirim Bearer Token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('access_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, (error) => {
  return Promise.reject(error);
});

// 3. Fungsi Helper Login
export const loginAdmin = async (email, password) => {
  const response = await api.post('/auth/login', { email, password });
  const { access_token, user } = response.data;
  
  // Simpan token ke localStorage
  localStorage.setItem('access_token', access_token);
  return user;
};

// 4. Contoh Fetch Inventaris Publik
export const getPublicInventory = async (page = 1) => {
  const response = await api.get(`/public/inventory?page=${page}`);
  return response.data;
};

// 5. Contoh Scan QR Code / Detail Barang
export const getItemByCode = async (itemCode) => {
  const response = await api.get(`/public/inventory/${itemCode}`);
  return response.data.data;
};

// 6. Contoh Catat Peminjaman Barang (Admin)
export const createLoan = async (loanData) => {
  const response = await api.post('/admin/loans', loanData);
  return response.data;
};

export default api;
```

---

## ⚙️ 6. Panduan Menjalankan Backend Laravel di Lokal

Jika rekan Frontend ingin menjalankan server Backend di laptop lokal:

1. **Clone repository & masuk ke direktori backend**:
   ```bash
   cd ledhak-integrated-portal
   ```
2. **Install dependensi PHP via Composer**:
   ```bash
   composer install
   ```
3. **Salin file konfigurasi lingkungan (.env)**:
   ```bash
   cp .env.example .env
   ```
4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```
5. **Jalankan Migrasi & Seeder Database (SQLite/MySQL)**:
   ```bash
   php artisan migrate:fresh --seed
   ```
6. **Jalankan Server Development**:
   ```bash
   php artisan serve
   ```
   *Server backend akan aktif di `http://127.0.0.1:8000` (atau `http://localhost:8000`).*
