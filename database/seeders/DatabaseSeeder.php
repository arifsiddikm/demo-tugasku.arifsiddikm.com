<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────
        //  ADMIN
        // ─────────────────────────────
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@tugasku.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // ─────────────────────────────
        //  DEMO USER 1 — Budi (developer)
        // ─────────────────────────────
        $budi = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'demo@tugasku.com',
            'password' => Hash::make('demo123'),
            'role'     => 'user',
        ]);
        $this->seedBudi($budi);

        // ─────────────────────────────
        //  DEMO USER 2 — Sari (desainer)
        // ─────────────────────────────
        $sari = User::create([
            'name'     => 'Sari Dewi',
            'email'    => 'sari@tugasku.com',
            'password' => Hash::make('sari123'),
            'role'     => 'user',
        ]);
        $this->seedSari($sari);

        // ─────────────────────────────
        //  DEMO USER 3 — Reza (manajer)
        // ─────────────────────────────
        $reza = User::create([
            'name'     => 'Reza Firmansyah',
            'email'    => 'reza@tugasku.com',
            'password' => Hash::make('reza123'),
            'role'     => 'user',
        ]);
        $this->seedReza($reza);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  BUDI — Web Developer
    // ─────────────────────────────────────────────────────────────────────
    private function seedBudi(User $user): void
    {
        // Proyek 1: Development
        $dev = Project::create([
            'user_id'    => $user->id,
            'name'       => 'Development',
            'color'      => '#3B82F6',
            'is_default' => true,
            'order'      => 0,
        ]);

        $todosDev = [
            ['Refactor AuthController', 'Pisahkan logic login dan register ke service layer agar lebih bersih.'],
            ['Integrasi payment gateway Midtrans', 'Setup sandbox, test webhook, dan handle callback status SETTLEMENT.'],
            ['Buat unit test untuk TaskController', 'Minimum 80% coverage, gunakan PHPUnit + factory.'],
            ['Optimasi query N+1 di dashboard admin', 'Gunakan eager loading dengan with() pada relasi users dan projects.'],
            ['Implementasi fitur export PDF laporan', 'Gunakan DomPDF, buat template blade yang rapi.'],
            ['Setup CI/CD pipeline GitHub Actions', 'Auto deploy ke server staging setiap push ke branch main.'],
            ['Buat API endpoint untuk mobile app', 'RESTful API dengan autentikasi Sanctum, dokumentasi Postman.'],
            ['Review PR dari tim frontend', 'Cek 3 pull request yang masih pending review sejak kemarin.'],
            ['Update dependensi composer', 'Jalankan composer outdated dan update yang aman.'],
            ['Perbaiki bug pagination infinite scroll', 'Bug: halaman ke-2 load ulang dari offset 0 setelah sort diubah.'],
            ['Implementasi cache Redis untuk query berat', 'Cache data dashboard admin selama 10 menit.'],
            ['Setup logging dengan Sentry', 'Integrasi error tracking untuk production environment.'],
        ];

        foreach ($todosDev as $i => [$title, $desc]) {
            Task::create([
                'user_id'    => $user->id,
                'project_id' => $dev->id,
                'title'      => $title,
                'description'=> $desc,
                'status'     => 'todo',
                'order'      => $i + 1,
            ]);
        }

        $donesDev = [
            ['Setup project Laravel 12', 'Instalasi fresh Laravel, konfigurasi .env, koneksi database MySQL.', 5],
            ['Buat migrasi database awal', 'Tabel users, projects, tasks lengkap dengan foreign key.', 4],
            ['Implementasi autentikasi login/register', 'Menggunakan AuthController custom tanpa Breeze.', 3],
            ['Deploy ke server VPS', 'Setup Nginx, PHP 8.3, SSL Let\'s Encrypt, konfigurasi .env production.', 2],
        ];

        foreach ($donesDev as [$title, $desc, $daysAgo]) {
            Task::create([
                'user_id'     => $user->id,
                'project_id'  => $dev->id,
                'title'       => $title,
                'description' => $desc,
                'status'      => 'done',
                'order'       => 0,
                'completed_at'=> now()->subDays($daysAgo),
            ]);
        }

        // Proyek 2: Belajar & Upskill
        $belajar = Project::create([
            'user_id' => $user->id,
            'name'    => 'Belajar & Upskill',
            'color'   => '#8B5CF6',
            'order'   => 1,
        ]);

        $todosBelajar = [
            ['Selesaikan kursus Docker & Kubernetes', 'Masih di chapter 4 dari 9. Target selesai minggu ini.'],
            ['Baca buku Clean Architecture', 'Lanjut bab 15 tentang boundaries.'],
            ['Ikut webinar Laravel Community Indonesia', 'Rabu 19:00 WIB, daftar di eventbrite.'],
            ['Praktik TDD dengan test-first approach', 'Buat fitur kecil menggunakan metodologi TDD penuh.'],
            ['Pelajari Vue 3 Composition API', 'Fokus di reactivity system dan composables.'],
        ];

        foreach ($todosBelajar as $i => [$title, $desc]) {
            Task::create([
                'user_id'    => $user->id,
                'project_id' => $belajar->id,
                'title'      => $title,
                'description'=> $desc,
                'status'     => 'todo',
                'order'      => $i + 1,
            ]);
        }

        Task::create([
            'user_id'     => $user->id,
            'project_id'  => $belajar->id,
            'title'       => 'Selesaikan kursus Tailwind CSS Advanced',
            'description' => 'Kursus di Udemy, durasi 8 jam. Fokus di custom config dan animasi.',
            'status'      => 'done',
            'order'       => 0,
            'completed_at'=> now()->subDays(3),
        ]);

        // Proyek 3: Kehidupan Pribadi
        $personal = Project::create([
            'user_id' => $user->id,
            'name'    => 'Kehidupan Pribadi',
            'color'   => '#F59E0B',
            'order'   => 2,
        ]);

        $todosPersonal = [
            ['Bayar tagihan internet', 'Jatuh tempo tanggal 20, bayar via m-banking.'],
            ['Servis motor', 'Sudah 3 bulan belum servis, ganti oli sekalian.'],
            ['Kirim CV ke startup fintech', 'Posisi Backend Developer, kirim ke karir@fintech.co.id.'],
        ];

        foreach ($todosPersonal as $i => [$title, $desc]) {
            Task::create([
                'user_id'    => $user->id,
                'project_id' => $personal->id,
                'title'      => $title,
                'description'=> $desc,
                'status'     => 'todo',
                'order'      => $i + 1,
            ]);
        }

        Task::create([
            'user_id'     => $user->id,
            'project_id'  => $personal->id,
            'title'       => 'Perpanjang SIM A',
            'description' => 'Bawa KTP asli, foto 3x4, datang ke Satpas sebelum jam 10.',
            'status'      => 'done',
            'order'       => 0,
            'completed_at'=> now()->subDays(7),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  SARI — UI/UX Designer
    // ─────────────────────────────────────────────────────────────────────
    private function seedSari(User $user): void
    {
        // Proyek 1: Desain UI
        $desain = Project::create([
            'user_id'    => $user->id,
            'name'       => 'Desain UI/UX',
            'color'      => '#EC4899',
            'is_default' => true,
            'order'      => 0,
        ]);

        $todosDesain = [
            ['Buat prototype landing page baru', 'Gunakan Figma, ikuti brand guideline yang sudah disetujui.'],
            ['Revisi desain halaman checkout', 'Feedback dari user testing: flow terlalu panjang, sederhanakan jadi 2 step.'],
            ['Buat icon set untuk aplikasi mobile', '24 icon dalam format SVG, dua varian: outline dan filled.'],
            ['Desain sistem notifikasi in-app', 'Toast, badge, dan panel notifikasi. Buat semua state-nya.'],
            ['User research untuk fitur dark mode', 'Survey ke 20 pengguna aktif, analisis kebutuhan.'],
            ['Update design system komponen button', 'Tambah varian ghost dan destructive, update dokumentasi.'],
            ['Buat animasi onboarding 3 slide', 'Gunakan Lottie, file size maksimal 150KB per animasi.'],
        ];

        foreach ($todosDesain as $i => [$title, $desc]) {
            Task::create([
                'user_id'    => $user->id,
                'project_id' => $desain->id,
                'title'      => $title,
                'description'=> $desc,
                'status'     => 'todo',
                'order'      => $i + 1,
            ]);
        }

        $donesDesain = [
            ['Redesign halaman login', 'Dark background, kartu putih, gradasi biru-ungu. Sudah approved klien.', 6],
            ['Buat mood board Q2 2026', 'Kumpulkan referensi visual dari Dribbble dan Behance.', 8],
            ['Handoff desain ke developer', 'Export asset, buat spesifikasi spacing, warna, dan tipografi di Zeplin.', 2],
        ];

        foreach ($donesDesain as [$title, $desc, $daysAgo]) {
            Task::create([
                'user_id'     => $user->id,
                'project_id'  => $desain->id,
                'title'       => $title,
                'description' => $desc,
                'status'      => 'done',
                'order'       => 0,
                'completed_at'=> now()->subDays($daysAgo),
            ]);
        }

        // Proyek 2: Konten & Media Sosial
        $konten = Project::create([
            'user_id' => $user->id,
            'name'    => 'Konten & Media Sosial',
            'color'   => '#F97316',
            'order'   => 1,
        ]);

        $todosKonten = [
            ['Buat carousel Figma tips Instagram', '5 slide, posting hari Kamis pagi jam 09.00.'],
            ['Tulis artikel "10 Plugin Figma Terbaik 2026"', 'Publish di Medium dan LinkedIn, target 1500 kata.'],
            ['Desain thumbnail YouTube channel', 'Konsisten dengan template yang ada, ukuran 1280x720.'],
            ['Siapkan konten TikTok speed drawing', 'Rekam proses desain landing page, edit jadi 60 detik.'],
        ];

        foreach ($todosKonten as $i => [$title, $desc]) {
            Task::create([
                'user_id'    => $user->id,
                'project_id' => $konten->id,
                'title'      => $title,
                'description'=> $desc,
                'status'     => 'todo',
                'order'      => $i + 1,
            ]);
        }

        Task::create([
            'user_id'     => $user->id,
            'project_id'  => $konten->id,
            'title'       => 'Posting portofolio di Dribbble',
            'description' => 'Upload 3 shot terbaru: dashboard, mobile app, dan landing page.',
            'status'      => 'done',
            'order'       => 0,
            'completed_at'=> now()->subDays(4),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  REZA — Project Manager
    // ─────────────────────────────────────────────────────────────────────
    private function seedReza(User $user): void
    {
        // Proyek 1: Manajemen Tim
        $tim = Project::create([
            'user_id'    => $user->id,
            'name'       => 'Manajemen Tim',
            'color'      => '#10B981',
            'is_default' => true,
            'order'      => 0,
        ]);

        $todosTim = [
            ['Review performa Q1 semua anggota tim', 'Isi form penilaian di HR system, deadline Jumat ini.'],
            ['Jadwalkan sprint planning Sprint 18', 'Booking meeting room B, undang 6 anggota tim, siapkan backlog.'],
            ['Buat laporan progres proyek ke klien', 'Laporan bulanan format PPT, kirim ke pak Hendro sebelum jam 17.'],
            ['Follow up proposal tambahan budget', 'Email ke finance, lampirkan breakdown biaya dan justifikasi.'],
            ['Onboarding developer baru: Fahmi', 'Siapkan akun GitHub, Slack, Jira. Briefing proyek hari Senin.'],
            ['Evaluasi tools manajemen proyek', 'Bandingkan Jira vs Linear vs Asana, buat rekomendasi untuk tim.'],
            ['Susun OKR Q3 2026 untuk divisi IT', 'Koordinasi dengan leads masing-masing squad, deadline minggu depan.'],
            ['Mediasi konflik antara frontend dan backend terkait API contract', 'Jadwalkan meeting alignment besok siang.'],
        ];

        foreach ($todosTim as $i => [$title, $desc]) {
            Task::create([
                'user_id'    => $user->id,
                'project_id' => $tim->id,
                'title'      => $title,
                'description'=> $desc,
                'status'     => 'todo',
                'order'      => $i + 1,
            ]);
        }

        $donesTim = [
            ['Sprint retrospective Sprint 17', 'Catat action items: improve code review SLA dan update definition of done.', 3],
            ['Hiring backend developer level mid', 'Wawancara 5 kandidat, pilih Fahmi sebagai yang terbaik.', 5],
            ['Presentasi roadmap Q2 ke stakeholder', 'Presentasi 30 menit di board meeting, semua approved.', 7],
            ['Setup Jira project baru: TugasKu Mobile', 'Konfigurasi board, sprint, dan epic sesuai struktur tim.', 9],
        ];

        foreach ($donesTim as [$title, $desc, $daysAgo]) {
            Task::create([
                'user_id'     => $user->id,
                'project_id'  => $tim->id,
                'title'       => $title,
                'description' => $desc,
                'status'      => 'done',
                'order'       => 0,
                'completed_at'=> now()->subDays($daysAgo),
            ]);
        }

        // Proyek 2: Administrasi
        $admin = Project::create([
            'user_id' => $user->id,
            'name'    => 'Administrasi',
            'color'   => '#64748B',
            'order'   => 1,
        ]);

        $todosAdmin = [
            ['Perpanjang lisensi software Figma tim', '5 seat, tagihan tahunan, koordinasi ke finance.'],
            ['Update SOP development workflow', 'Revisi dokumen berdasarkan lessons learned Sprint 17.'],
            ['Isi laporan absensi bulan April', 'Download dari HR portal, tandatangani, scan, kirim ke HRD.'],
        ];

        foreach ($todosAdmin as $i => [$title, $desc]) {
            Task::create([
                'user_id'    => $user->id,
                'project_id' => $admin->id,
                'title'      => $title,
                'description'=> $desc,
                'status'     => 'todo',
                'order'      => $i + 1,
            ]);
        }

        Task::create([
            'user_id'     => $user->id,
            'project_id'  => $admin->id,
            'title'       => 'Kirim invoice proyek klien A',
            'description' => 'Invoice Rp 45.000.000 untuk milestone kedua. Kirim ke finance@klien.com.',
            'status'      => 'done',
            'order'       => 0,
            'completed_at'=> now()->subDays(2),
        ]);

        // Proyek 3: Pengembangan Diri
        $growth = Project::create([
            'user_id' => $user->id,
            'name'    => 'Pengembangan Diri',
            'color'   => '#F59E0B',
            'order'   => 2,
        ]);

        $todosGrowth = [
            ['Selesaikan buku "The Manager\'s Path"', 'Lagi di chapter 6: Managing Multiple Teams.'],
            ['Ikut workshop Agile Leadership bulan ini', 'Daftar di Eventbrite, budget Rp 750.000 sudah disetujui.'],
            ['Podcast episode mingguan: tentang remote culture', 'Record 30 menit, edit, upload ke Spotify.'],
        ];

        foreach ($todosGrowth as $i => [$title, $desc]) {
            Task::create([
                'user_id'    => $user->id,
                'project_id' => $growth->id,
                'title'      => $title,
                'description'=> $desc,
                'status'     => 'todo',
                'order'      => $i + 1,
            ]);
        }
    }
}
