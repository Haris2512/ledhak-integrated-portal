# 💻 Frontend Integration Guide (Panduan Integrasi FE)
> **UKM LeDHaK UNHAS Integrated Portal Backend**

Dokumen ini ditujukan khusus untuk **Developer Frontend (FE)** untuk membantu mempermudah proses integrasi antarmuka (UI) dengan REST API **Backend Laravel**.

---

## 📑 Daftar Isi
1. [Ringkasan & Konfigurasi Utama](#1-ringkasan--konfigurasi-utama)
2. [Alur Otentikasi Admin (Sanctum Bearer Token)](#2-alur-otentikasi-admin-sanctum-bearer-token)
3. [Daftar Endpoint API](#3-daftar-endpoint-api)
   - [Public Endpoints (Tanpa Login)](#31-public-endpoints-tanpa-login)
   - [Admin Endpoints (Perlu Bearer Token)](#32-admin-endpoints-perlu-bearer-token)
4. [Penanganan Error & Validasi](#4-penanganan-error--validasi)
5. [Contoh Kode Integrasi (JavaScript / Axios)](#5-contoh-kode-integrasi-javascript--axios)
6. [Langkah-Langkah Setup Server Lokal](#6-langkah-langkah-setup-server-lokal)

---

## 1. Ringkasan & Konfigurasi Utama

- **Base URL API (Lokal)**: `http://localhost:8000/api`
- **Default Headers**:
  ```json
  {
    "Content-Type": "application/json",
    "Accept": "application/json"
  }
  ```
- **Kredensial Admin Default (Development)**:
  - **Email**: `admin@ledhak-unhas.org`
  - **Password**: `password123`

---

## 2. Alur Otentikasi Admin (Sanctum Bearer Token)

1. Tim FE melakukan request `POST /api/auth/login` dengan `email` dan `password`.
2. Backend merespon dengan membawa payload `access_token` (contoh: `1|qlx89A...`).
3. Simpan `access_token` ini di `localStorage`, `sessionStorage`, atau state management (Zustand/Redux/Pinia).
4. Untuk setiap request ke endpoint `/api/admin/*`, tambahkan header:
   ```http
   Authorization: Bearer <access_token>
   ```
5. Jika API mengembalikan status `401 Unauthorized`, hapus token dan redirect user kembali ke halaman Login Admin.

---

## 3. Daftar Endpoint API

### 3.1 Public Endpoints (Tanpa Login)

| Method | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `GET` | `/api/public/profile` | Mengambil info profil UKM LeDHaK (Visi, Misi, Kontak) |
| `GET` | `/api/public/articles` | List berita/artikel publik (Paginated, `?page=1`) |
| `GET` | `/api/public/articles/{slug}` | Detail berita berdasarkan slug artikel |
| `GET` | `/api/public/inventory` | List katalog 77 barang inventaris (Paginated, `?page=1`) |
| `GET` | `/api/public/inventory/{item_code}` | Detail barang berdasarkan kode barang (Hasil Scan QR) |

---

### 3.2 Admin Endpoints (Perlu Bearer Token)

#### 📦 Manajemen Inventaris Barang (`/api/admin/items`)
| Method | Endpoint | Body Payload | Keterangan |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/admin/items` | - | List seluruh barang + log & riwayat pinjam |
| `POST` | `/api/admin/items` | `{ "name", "category", "description", "status" }` | Tambah barang baru (Auto item_code & QR) |
| `GET` | `/api/admin/items/{id}` | - | Detail spesifik barang |
| `PUT` | `/api/admin/items/{id}` | `{ "name", "category", "description", "status" }` | Update data barang |
| `PATCH` | `/api/admin/items/{id}/status` | `{ "status": "Tersedia"\|"Dipinjam"\|"Perbaikan", "notes" }` | Quick status change |
| `DELETE` | `/api/admin/items/{id}` | - | Hapus barang inventaris |

#### 🤝 Peminjaman & Pengembalian (`/api/admin/loans`)
| Method | Endpoint | Body Payload | Keterangan |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/admin/loans` | `{ "item_id", "borrower_name", "borrower_phone", "loan_date", "return_date" }` | Catat peminjaman baru (Auto sync Google Sheets & status -> Dipinjam) |
| `POST` | `/api/admin/loans/{id}/return` | - | Konfirmasi pengembalian (Auto sync Google Sheets & status -> Tersedia) |

#### 📰 Manajemen Artikel (`/api/admin/articles`)
| Method | Endpoint | Body Payload | Keterangan |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/admin/articles` | - | List semua artikel (Draft & Published) |
| `POST` | `/api/admin/articles` | `{ "title", "content", "status": "Draft"\|"Published" }` | Tambah artikel baru |
| `GET` | `/api/admin/articles/{id}` | - | Detail artikel |
| `PUT` | `/api/admin/articles/{id}` | `{ "title", "content", "status" }` | Update artikel |
| `DELETE` | `/api/admin/articles/{id}` | - | Hapus artikel |

---

## 4. Penanganan Error & Validasi

Backend menggunakan format standar HTTP status code:
- **`200 OK` / `201 Created`**: Request berhasil diproses.
- **`401 Unauthorized`**: Token tidak dikirimkan atau sudah kadaluarsa.
- **`404 Not Found`**: Data tidak ditemukan (misal: slug/item_code salah).
- **`422 Unprocessable Content`**: Gagal validasi data.

### Format Response Error Validasi (422):
```json
{
  "message": "The item_id field is required.",
  "errors": {
    "item_id": [
      "Barang Spanduk saat ini tidak tersedia untuk dipinjam (Status: Dipinjam)."
    ],
    "borrower_name": [
      "Nama peminjam wajib diisi."
    ]
  }
}
```

---

## 5. Contoh Kode Integrasi (JavaScript / Axios)

```javascript
import axios from 'axios';

// API Client Setup
const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

// Auto Insert Bearer Token
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('access_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Contoh Fungsi API
export const apiService = {
  // Login Admin
  login: async (email, password) => {
    const res = await apiClient.post('/auth/login', { email, password });
    localStorage.setItem('access_token', res.data.access_token);
    return res.data;
  },

  // Get Catalog Barang
  getInventory: async (page = 1) => {
    const res = await apiClient.get(`/public/inventory?page=${page}`);
    return res.data;
  },

  // Scan QR Code / Check Item Code
  scanQR: async (itemCode) => {
    const res = await apiClient.get(`/public/inventory/${itemCode}`);
    return res.data.data;
  },

  // Catat Peminjaman (Admin)
  createLoan: async (loanPayload) => {
    const res = await apiClient.post('/admin/loans', loanPayload);
    return res.data;
  },

  // Pengembalian Barang (Admin)
  returnLoan: async (loanId) => {
    const res = await apiClient.post(`/admin/loans/${loanId}/return`);
    return res.data;
  }
};
```

---

## 6. Langkah-Langkah Setup Server Lokal

Bagi rekan FE yang ingin menyalakan Backend Laravel di komputer lokal:

```bash
# 1. Masuk ke folder proyek
cd ledhak-integrated-portal

# 2. Install dependensi composer
composer install

# 3. Salin file .env
cp .env.example .env

# 4. Generate Application Key
php artisan key:generate

# 5. Migration & Seed Data 77 Barang + Admin Default
php artisan migrate:fresh --seed

# 6. Jalankan Server Dev
php artisan serve
```

*Backend siap diakses pada URL: `http://localhost:8000/api`*
