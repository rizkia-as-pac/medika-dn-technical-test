# Online Queue Application

**Backend Programmer Technical Test**

---

## Identitas Peserta

- **Nama**: Rizkia Adhy Syahputra
- **Posisi Dilamar**: Back End Programmer
- **Perusahaan**: PT Medika Digital Nusantara
- **Email**: rizkia.as.pac@gmail.com
- **CV**: https://drive.google.com/file/d/18HpcLSwnoX0rp6gcg4pDst4u-nDD_ogU/view?usp=sharing
---

## Deskripsi Singkat

Aplikasi **Antrian Online Sederhana** berbasis **Laravel (API)** dengan **frontend statis (HTML, CSS, JavaScript)**.  
Fitur:

- Admin login
- Admin mengeluarkan nomor antrian (issue)
- Admin menavigasi antrian (next / prev)
- Pengguna melihat nomor antrian saat ini dan daftar antrian secara realtime

---

## Arsitektur Singkat

- **Backend**: Laravel REST API (stateless, token-based auth)
- **Frontend**: Static HTML + CSS + JavaScript
- **Database**: PostgreSQL
- **Auth**: Token disimpan di `localStorage`
- **Realtime**: Polling setiap 2 detik (tanpa websocket)

---

## Tech Stack

### Backend

- PHP 8+
- Laravel 12
- PostgreSQL
- Laravel Sanctum (token authentication)

### Frontend

- HTML5
- CSS3
- Vanilla JavaScript (Fetch API)

---

## Struktur Proyek (Ringkas)

```
queue-app/
├── app/
│ ├── Http/Controllers/Admin
│ │ ├── AuthController.php
│ │ └── QueueController.php
│ └── Models
│ ├── Queue.php
│ └── QueueCounter.php
├── database/migrations
├── routes/api.php
├── public/
│ ├── index.html
│ ├── login.html
│ └── assets/
│ ├── css/
│ └── js/
├── .gitignore
└── README.md
```

## Cara Menjalankan Aplikasi

### Clone Repository

```bash
git clone https://github.com/rizkia-as-pac/medika-dn-technical-test.git
cd queue-app
```

### Install Dependency Backend

```bash
composer install
```

### Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database PostgreSQL di .env

### Jalankan Migration

```bash
php artisan migrate
```

### Jalankan Migration

```bash
php artisan serve
```

Akses aplikasi di:

```
http://127.0.0.1:8000

```

## Akun Admin (Default)

```bash
Email    : admin@queue.test
Password : admin12345
```

## Halaman Aplikasi

```bash
Email    : admin@queue.test
Password : admin12345
```

| Halaman             | URL         |
| ------------------- | ----------- |
| Login Admin         | /login.html |
| Admin Panel & Queue | /index.html |

## Endpoint API Utama

### Auth

POST /api/admin/login

### Admin Queue

POST /api/admin/queue/issue
POST /api/admin/queue/next
POST /api/admin/queue/prev

### Public

GET /api/queue/public
