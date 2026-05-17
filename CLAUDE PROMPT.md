# CLAUDE PROMPT — TugasKu (PRD Lengkap)

> Upload file ini ke Claude baru dan minta ia membangun ulang website TugasKu dari nol.

---

## INSTRUKSI UNTUK CLAUDE

Kamu adalah expert full-stack developer Laravel. Bangun website **TugasKu** secara lengkap sesuai PRD berikut. Mulai dari struktur folder, migration, seeder, controller, middleware, view — semua. Berikan semua file dalam bentuk kode lengkap yang siap pakai.

---

## 1. OVERVIEW PROYEK

| Item | Detail |
|------|--------|
| **Nama** | TugasKu |
| **Konsep** | Aplikasi web kelola tugas / to-do list berbasis proyek/kategori |
| **Tech Stack** | Laravel 12 · PHP 8.3 · SQLite (default) / MySQL · Tailwind CSS CDN · Alpine.js (opsional) · SweetAlert2 · Font Awesome 6 · Google Fonts (Plus Jakarta Sans) |
| **Role** | `admin` dan `user` |
| **Auth** | Custom (bukan Breeze/Jetstream) |
| **Admin panel** | `/webmin/dashboard` |
| **User dashboard** | `/dashboard` |

---

## 2. DATABASE SCHEMA

### Tabel `users`
```
id, name, email, password, role (enum: admin|user), is_active (boolean, default true), remember_token, email_verified_at, created_at, updated_at
```

### Tabel `projects`
```
id, user_id (FK→users), name, color (hex, default #3B82F6), order (integer, default 0), is_default (boolean, default false), created_at, updated_at
```

### Tabel `tasks`
```
id, user_id (FK→users), project_id (FK→projects), title, description (nullable text), status (enum: todo|done, default todo), order (integer, default 0), completed_at (nullable timestamp), returned_at (nullable timestamp), created_at, updated_at
```

Cascade delete: hapus user → hapus projects & tasks-nya. Hapus project → hapus tasks-nya.

---

## 3. AUTH & MIDDLEWARE

### AuthController (`/app/Http/Controllers/Auth/AuthController.php`)
- `loginForm()` — tampilkan halaman login, redirect jika sudah login
- `login()` — validasi, cek `is_active`, redirect ke admin atau user dashboard
- `registerForm()` — tampilkan halaman register
- `register()` — buat user baru dengan role `user`, buat **default project** "Main Project" (#3B82F6, is_default=true), buat 2 sample task awal, auto login
- `logout()` — invalidate session, redirect ke login

### Middleware
- `AdminMiddleware` → cek `auth()->user()->isAdmin()`, jika tidak redirect ke `/dashboard`
- `UserMiddleware` → cek role `user` atau block admin masuk ke user area

### bootstrap/app.php — daftarkan middleware alias:
```php
'admin.access' => AdminMiddleware::class,
'user.access'  => UserMiddleware::class,
```

---

## 4. ROUTES (`routes/web.php`)

```
GET  /                          → LandingController@index          [name: landing]
GET  /login                     → AuthController@loginForm          [guest]
POST /login                     → AuthController@login             [guest]
GET  /register                  → AuthController@registerForm      [guest]
POST /register                  → AuthController@register          [guest]
POST /logout                    → AuthController@logout            [auth]

--- User Area [auth, user.access] ---
GET  /dashboard                         → TaskController@index
GET  /dashboard/project/{projectId}     → TaskController@index
GET  /tasks/load                        → TaskController@loadTasks
POST /tasks                             → TaskController@store
PUT  /tasks/{task}                      → TaskController@update
DELETE /tasks/{task}                    → TaskController@destroy
POST /tasks/{task}/complete             → TaskController@complete
POST /tasks/{task}/return               → TaskController@returnTask
POST /projects                          → ProjectController@store
PUT  /projects/{project}                → ProjectController@update
DELETE /projects/{project}              → ProjectController@destroy

--- Admin Area [auth, admin.access] prefix: /webmin ---
GET  /webmin/dashboard                  → AdminController@dashboard
GET  /webmin/users                      → AdminController@users
POST /webmin/users/{user}/toggle        → AdminController@toggleUser
DELETE /webmin/users/{user}             → AdminController@deleteUser
GET  /webmin/admins                     → AdminController@admins
POST /webmin/admins                     → AdminController@storeAdmin
PUT  /webmin/admins/{admin}             → AdminController@updateAdmin
DELETE /webmin/admins/{admin}           → AdminController@deleteAdmin
```

---

## 5. CONTROLLERS

### TaskController (`/app/Http/Controllers/User/TaskController.php`)

**`index($projectId = null)`**
- Ambil semua proyek user diurutkan `order ASC`
- Jika kosong, buat default project otomatis
- Tentukan `$currentProject` dari `$projectId` atau first project
- Return view `dashboard.index` dengan `$projects` dan `$currentProject`

**`store(Request $request)`** — JSON response
- Validasi: `title` required, `project_id` required & exists
- Verifikasi project milik user yang login
- Hitung `max order + 1`
- Buat task dengan status `todo`
- Return `{ success: true, task: formatTask() }`

**`update(Request $request, Task $task)`** — JSON response
- Cek `task->user_id === Auth::id()`, jika tidak return 403
- Update `title` dan `description`
- Return `{ success: true, task: formatTask() }`

**`destroy(Task $task)`** — JSON response
- Cek ownership, soft-delete → `task->delete()`
- Return `{ success: true }`

**`complete(Task $task)`** — JSON response
- Set `status = done`, `completed_at = now()`, `returned_at = null`

**`returnTask(Task $task)`** — JSON response
- Set `status = todo`, `returned_at = now()`, `completed_at = null`

**`loadTasks(Request $request)`** — JSON response (lazy loading)
- Param: `project_id`, `status` (todo|done), `sort` (asc|desc), `offset`
- Limit: 20 untuk todo, 10 untuk done
- Return `{ tasks[], hasMore, total }`

**`formatTask(Task $task)`** — private helper
```php
return [
    'id', 'title', 'description', 'status', 'order', 'project_id',
    'created_at'  => format('d M Y, H:i'),
    'completed_at'=> format('d M Y, H:i'),
    'returned_at' => format('d M Y, H:i'),
    'updated_at'  => format('d M Y, H:i'),
];
```

### ProjectController (`/app/Http/Controllers/User/ProjectController.php`)
- `store` — buat project baru milik user, hitung order otomatis, return JSON
- `update` — update name & color, cek ownership
- `destroy` — cek bukan `is_default`, hapus project beserta tasks-nya

### AdminController (`/app/Http/Controllers/Admin/AdminController.php`)
- `dashboard` — statistik: totalUsers, totalProjects, totalTasks, doneTasks, todoTasks, recent users (5 terakhir)
- `users` — list semua user role=user dengan pagination 15, search by name/email
- `toggleUser` — flip `is_active`
- `deleteUser` — hapus user (+ cascade)
- `admins` — list semua user role=admin
- `storeAdmin` — buat admin baru (validasi unique email)
- `updateAdmin` — update name, email, password (opsional)
- `deleteAdmin` — tidak bisa hapus diri sendiri

---

## 6. MODELS

### User
- Fillable: name, email, password, role, is_active
- Hidden: password, remember_token
- Casts: email_verified_at (datetime), password (hashed), is_active (boolean)
- Methods: `isAdmin()`, `projects()`, `tasks()`
- Accessors: `getTasksCountAttribute()`, `getDoneTasksCountAttribute()`, `getProjectsCountAttribute()`

### Project
- Fillable: user_id, name, color, order, is_default
- Casts: is_default (boolean)
- Relations: `user()`, `tasks()`, `todoTasks()` (where status=todo), `doneTasks()` (where status=done)

### Task
- Fillable: user_id, project_id, title, description, status, order, completed_at, returned_at
- Casts: completed_at (datetime), returned_at (datetime)
- Relations: `user()`, `project()`

---

## 7. VIEWS — STRUKTUR

```
resources/views/
├── layouts/
│   ├── app.blade.php         (layout user)
│   └── admin.blade.php       (layout admin)
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── landing/
│   └── index.blade.php
├── dashboard/
│   └── index.blade.php       (main user page)
└── admin/
    ├── dashboard.blade.php
    ├── users.blade.php
    └── admins.blade.php
```

---

## 8. HALAMAN LOGIN (`resources/views/auth/login.blade.php`)

**Design:** Background dark navy `#0f172a`, kartu putih dengan border-radius 20px, shadow besar.  
**Font:** Plus Jakarta Sans (Google Fonts).  
**Komponen:**
- Logo TugasKu (ikon check-double biru, teks bold)
- Judul "Selamat Datang Kembali"
- **Demo Autofill Section** — kotak abu muda dengan grid 2 kolom berisi tombol-tombol demo akun:
  - Admin (full width, warna kuning muda) → autofill email & password admin
  - Budi Santoso / Developer (biru)
  - Sari Dewi / UI/UX Designer (pink)
  - Reza Firmansyah / Project Manager (hijau)
- Form email + password (dengan toggle show/hide)
- Checkbox "Ingat saya"
- Tombol submit biru gradien
- Link ke halaman register
- Error message via SweetAlert2 jika session error

**JavaScript autofill:**
```js
function autofill(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
    // tampilkan password sementara lalu hide
}
```

---

## 9. DASHBOARD USER (`resources/views/dashboard/index.blade.php`)

**Layout:** Full-height flex (sidebar + main), tidak ada scroll body.

**Sidebar (280px, dark navy):**
- Logo + green dot online indicator
- Avatar inisial + nama + email user
- List proyek dengan warna dot, badge jumlah todo, tombol hapus (bukan default)
- Tombol tambah proyek (plus icon)
- Tombol logout (merah, SweetAlert confirm)

**Main area:**
- Top bar sticky: nama proyek + badge total aktif, sort select (asc/desc), toggle layout (list/masonry grid), tombol "Tugas Baru"
- **Quick add form** (animasi fadeIn, tersembunyi by default): field title + textarea desc + tombol tambah
- Section **Tugas Aktif** dengan counter badge biru
- Container tasks (lazy load via AJAX, 20 per batch), "Muat 20 Tugas Lagi"
- Divider "Sudah Selesai"
- Section **Selesai** dengan counter badge hijau, opacity 75%
- Container done tasks (10 per batch), "Muat 20 Tugas Selesai Lagi"

**Task card design:**
- Judul bold, deskripsi truncated 2 baris
- Timestamp (dibuat / selesai)
- Tombol: ✓ Complete / ↩ Kembalikan, ✏️ Edit, 🗑️ Hapus
- Done card: strikethrough title, background hijau muda

**Modal Task Detail:**
- Tampil judul + deskripsi penuh
- Timestamps lengkap
- Tombol complete/return + edit + hapus

**Modal Edit Task:**
- Input title + textarea description
- Tombol Simpan

**Modal Tambah Proyek:**
- Input nama + color picker (predefined palette 10 warna)
- Tombol simpan

**Modal Edit Proyek:**
- Sama seperti tambah + nama proyek saat ini

**SweetAlert2** untuk konfirmasi hapus task, hapus proyek, logout.

**AJAX Flow (loadTasks):**
```
GET /tasks/load?project_id=X&status=todo&sort=asc&offset=0
→ render task cards
→ update counters
→ show/hide load-more button
```

---

## 10. HALAMAN ADMIN

### Admin Dashboard (`/webmin/dashboard`)
4 stat cards: Total Pengguna, Total Proyek, Tugas Selesai, Tugas Aktif.  
Tabel "Pengguna Terbaru" (5 baris): nama, email, jumlah proyek, jumlah tugas, status aktif.

### Kelola Pengguna (`/webmin/users`)
Tabel semua user (role=user): nama, email, proyek, tugas selesai/total, status badge, tombol Aktif/Nonaktif + Hapus.

### Kelola Admin (`/webmin/admins`)
Tabel admin: nama, email, aksi edit + hapus.  
Form tambah admin di atas tabel.  
Modal edit admin.

---

## 11. DATABASE SEEDER (`database/seeders/DatabaseSeeder.php`)

Buat akun-akun berikut:

| Nama | Email | Password | Role |
|------|-------|----------|------|
| Administrator | admin@tugasku.com | admin123 | admin |
| Budi Santoso | demo@tugasku.com | demo123 | user |
| Sari Dewi | sari@tugasku.com | sari123 | user |
| Reza Firmansyah | reza@tugasku.com | reza123 | user |

**Budi** — 3 proyek: Development (12 todo + 4 done), Belajar & Upskill (5 todo + 1 done), Kehidupan Pribadi (3 todo + 1 done).  
**Sari** — 2 proyek: Desain UI/UX (7 todo + 3 done), Konten & Media Sosial (4 todo + 1 done).  
**Reza** — 3 proyek: Manajemen Tim (8 todo + 4 done), Administrasi (3 todo + 1 done), Pengembangan Diri (3 todo).

Semua tugas harus punya `description` yang informatif dan kontekstual sesuai persona.

---

## 12. STYLING GLOBAL

- Font: `Plus Jakarta Sans` dari Google Fonts
- Warna utama: `#3b82f6` (biru), `#6366f1` (ungu), `#22c55e` (hijau), `#ef4444` (merah)
- Background dark: `#0f172a`
- Border default: `#e2e8f0`
- Border-radius card: 12–16px
- Transitions: `0.2s ease` semua interaksi
- Box-shadow card: `0 2px 12px rgba(0,0,0,0.08)`
- Gunakan **Tailwind CSS CDN** + inline style untuk komponen spesifik
- SweetAlert2 CDN untuk semua confirm/alert
- Font Awesome 6 CDN untuk ikon

---

## 13. FILE KONFIGURASI PENTING

### `.env` (database SQLite default)
```
DB_CONNECTION=sqlite
```

### `composer.json` — dependencies minimal
```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^12.0"
  }
}
```

### Instalasi
```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
```

---

## 14. FITUR LENGKAP CHECKLIST

### Auth
- [x] Login dengan email + password
- [x] Register akun baru (auto buat default project)
- [x] Logout dengan konfirmasi SweetAlert2
- [x] Redirect berdasarkan role (admin vs user)
- [x] Cek `is_active` saat login
- [x] Remember me (checkbox)
- [x] Autofill demo akun di halaman login

### User — Task Management
- [x] Lihat tugas per proyek
- [x] Tambah tugas (title + description)
- [x] Edit tugas
- [x] Hapus tugas (konfirmasi)
- [x] Tandai selesai (complete)
- [x] Kembalikan ke aktif (return)
- [x] Lazy loading tugas (20 per batch untuk todo, 10 untuk done)
- [x] Sort tugas: terlama / terbaru
- [x] Layout toggle: list view / masonry grid
- [x] Modal detail tugas (tampil full description + timestamps)

### User — Project Management
- [x] Lihat daftar proyek di sidebar
- [x] Tambah proyek baru (nama + warna)
- [x] Edit proyek (nama + warna)
- [x] Hapus proyek (tidak bisa hapus default project)
- [x] Badge counter tugas aktif per proyek
- [x] Navigasi antar proyek

### Admin Panel
- [x] Dashboard statistik (4 card)
- [x] Tabel pengguna terbaru
- [x] CRUD pengguna (toggle aktif, hapus)
- [x] CRUD admin (tambah, edit, hapus — tidak bisa hapus diri sendiri)

### UX / UI
- [x] Responsive sidebar (dark navy)
- [x] Sticky top bar
- [x] SweetAlert2 untuk semua konfirmasi
- [x] Animasi fadeIn untuk quick add form
- [x] Green dot "online" indicator di sidebar
- [x] Error handling AJAX (toast notification)
- [x] Empty state (jika belum ada tugas)

---

*Prompt ini dibuat untuk membangun ulang TugasKu v1.0 dari nol. Gunakan sebagai acuan lengkap.*
