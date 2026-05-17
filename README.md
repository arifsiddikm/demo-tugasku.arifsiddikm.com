# TugasKu — Aplikasi Kelola Tugas

Aplikasi web manajemen tugas berbasis proyek/kategori dengan admin panel CMS, multi-user, dan tampilan yang bersih.

🌐 **Live Demo:** [demo-tugasku.arifsiddikm.com](https://demo-tugasku.arifsiddikm.com)

---

## Tech Stack

- **Backend:** PHP 8.3 + Laravel 12
- **Database:** SQLite / MySQL
- **Frontend:** Tailwind CSS CDN · SweetAlert2 · Font Awesome 6
- **Font:** Plus Jakarta Sans (Google Fonts)

---

## Fitur

**Dashboard Pengguna**
- Kelola tugas per proyek/kategori
- Tambah, edit, hapus, selesaikan, dan kembalikan tugas
- Lazy loading tugas (pagination AJAX)
- Sort terlama/terbaru · Toggle layout list & grid
- Tambah dan kelola proyek dengan warna kustom

**Admin Panel** (`/webmin/dashboard`)
- Statistik: total pengguna, proyek, tugas aktif & selesai
- CRUD pengguna (aktif/nonaktif, hapus)
- CRUD akun admin

---

## Demo Akun

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@tugasku.com | admin123 |
| User (Budi) | demo@tugasku.com | demo123 |
| User (Sari) | sari@tugasku.com | sari123 |
| User (Reza) | reza@tugasku.com | reza123 |

> Tersedia tombol autofill di halaman login untuk kemudahan demo.

---

## Instalasi

```bash
# 1. Clone repo
git clone https://github.com/arifsiddikm/tugasku.git
cd tugasku

# 2. Install dependencies
composer install

# 3. Copy dan konfigurasi .env
cp file env to .env and setting your password
php artisan key:generate

# 4. Setup database (SQLite default)
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# 5. Storage link
php artisan storage:link

# 6. Jalankan server
php artisan serve
```

Akses di `http://localhost:8000`

---

## Login Admin

```
URL   : http://localhost:8000/webmin/dashboard
Email : admin@tugasku.com
Pass  : admin123
```

---

## Konfigurasi MySQL (opsional)

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tugasku
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan ulang:
```bash
php artisan migrate
php artisan db:seed
```

---

### Support me on
<a href="https://saweria.co/arifsiddikm" target="_blank"><img src="https://user-images.githubusercontent.com/26188697/180601310-e82c63e4-412b-4c36-b7b5-7ba713c80380.png" alt="Sawer me" height="41" width="174"></a>
