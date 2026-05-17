<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TugasKu - Aplikasi manajemen tugas modern. Organisir proyek, kelola tugas, dan tingkatkan produktivitas harianmu.">
    <meta name="keywords" content="todo list, task manager, manajemen tugas, produktivitas, TugasKu, aplikasi tugas">
    <meta name="author" content="TugasKu">
    <meta property="og:title" content="TugasKu — Kelola Tugasmu, Raih Targetmu">
    <meta property="og:description" content="Aplikasi manajemen tugas sederhana, cepat, dan modern untuk produktivitas maksimal.">
    <meta property="og:type" content="website">
    <title>TugasKu — Kelola Tugasmu, Raih Targetmu</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-hero { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 40%, #1d4ed8 70%, #3b82f6 100%); }
        .gradient-text { background: linear-gradient(135deg, #60a5fa, #a78bfa, #f472b6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .card-glow { box-shadow: 0 0 0 1px rgba(255,255,255,0.08), 0 20px 60px rgba(0,0,0,0.3); }
        .feature-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(10px); transition: all 0.3s ease; }
        .feature-card:hover { background: rgba(255,255,255,0.08); border-color: rgba(99,179,237,0.3); transform: translateY(-4px); }
        .btn-cta { background: linear-gradient(135deg, #3b82f6, #6366f1); color: white; padding: 0.875rem 2.5rem; border-radius: 12px; font-weight: 700; font-size: 1.05rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 12px 35px rgba(59,130,246,0.5); }
        .btn-outline { background: transparent; color: white; padding: 0.875rem 2rem; border-radius: 12px; font-weight: 600; font-size: 1rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; border: 1.5px solid rgba(255,255,255,0.3); }
        .btn-outline:hover { background: rgba(255,255,255,0.1); border-color: white; }
        .nav-link { color: rgba(255,255,255,0.75); text-decoration: none; font-weight: 500; transition: color 0.2s; }
        .nav-link:hover { color: white; }
        .mock-task { background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
        .mock-check { width: 18px; height: 18px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.35); flex-shrink: 0; }
        .mock-check.done { background: #3b82f6; border-color: #3b82f6; display: flex; align-items: center; justify-content: center; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-12px); } }
        .float-anim { animation: float 4s ease-in-out infinite; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { opacity: 0; animation: fadeUp 0.8s ease forwards; }
        .fade-up-1 { animation-delay: 0.1s; }
        .fade-up-2 { animation-delay: 0.3s; }
        .fade-up-3 { animation-delay: 0.5s; }
        .fade-up-4 { animation-delay: 0.7s; }
        .stat-card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 1.5rem; text-align: center; }
    </style>
</head>
<body style="background:#0f172a; color:white;">

<!-- NAVBAR -->
<nav style="position:fixed;top:0;left:0;right:0;z-index:100;padding:1rem 0;backdrop-filter:blur(12px);background:rgba(15,23,42,0.85);border-bottom:1px solid rgba(255,255,255,0.07);">
    <div style="max-width:1100px;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;justify-content:space-between;">
        <a href="{{ route('landing') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-check-double" style="color:white;font-size:14px;"></i>
            </div>
            <span style="font-size:1.35rem;font-weight:800;color:white;letter-spacing:-0.03em;">TugasKu</span>
        </a>
        <div style="display:flex;align-items:center;gap:2rem;">
            <a href="#fitur" class="nav-link" style="font-size:0.9rem;">Fitur</a>
            <a href="#cara-kerja" class="nav-link" style="font-size:0.9rem;">Cara Kerja</a>
            <a href="#faq" class="nav-link" style="font-size:0.9rem;">FAQ</a>
            <a href="{{ route('login') }}" style="color:rgba(255,255,255,0.8);text-decoration:none;font-weight:600;font-size:0.9rem;">Masuk</a>
            <a href="{{ route('register') }}" class="btn-cta" style="padding:0.5rem 1.25rem;font-size:0.875rem;">Mulai Gratis</a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="gradient-hero" style="padding:10rem 1.5rem 6rem;min-height:100vh;display:flex;align-items:center;">
    <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
        <div>
            <div class="fade-up fade-up-1" style="display:inline-flex;align-items:center;gap:8px;background:rgba(99,102,241,0.2);border:1px solid rgba(99,102,241,0.35);border-radius:50px;padding:6px 16px;font-size:0.8rem;font-weight:600;color:#a5b4fc;margin-bottom:1.5rem;">
                <span style="width:6px;height:6px;background:#a5b4fc;border-radius:50%;"></span>
                Gratis untuk semua pengguna
            </div>
            <h1 class="fade-up fade-up-2" style="font-size:3.5rem;font-weight:900;line-height:1.1;letter-spacing:-0.04em;margin-bottom:1.5rem;">
                Kelola Tugasmu,<br><span class="gradient-text">Raih Targetmu.</span>
            </h1>
            <p class="fade-up fade-up-3" style="font-size:1.1rem;color:rgba(255,255,255,0.65);line-height:1.7;margin-bottom:2.5rem;max-width:450px;">
                TugasKu adalah aplikasi manajemen tugas yang simpel, cepat, dan modern. Organisir proyek, pantau progres, dan selesaikan lebih banyak setiap hari.
            </p>
            <div class="fade-up fade-up-4" style="display:flex;gap:1rem;flex-wrap:wrap;">
                <a href="{{ route('register') }}" class="btn-cta">
                    <i class="fas fa-rocket"></i> Mulai Sekarang — Gratis
                </a>
                <a href="{{ route('login') }}" class="btn-outline">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </a>
            </div>
            <div style="margin-top:2.5rem;display:flex;align-items:center;gap:1.5rem;">
                <div class="stat-card" style="padding:0.75rem 1.25rem;">
                    <div style="font-size:1.5rem;font-weight:800;color:#60a5fa;">{{ number_format($totalUsers) }}+</div>
                    <div style="font-size:0.75rem;color:rgba(255,255,255,0.5);margin-top:2px;">Pengguna Aktif</div>
                </div>
                <div class="stat-card" style="padding:0.75rem 1.25rem;">
                    <div style="font-size:1.5rem;font-weight:800;color:#a78bfa;">{{ number_format($totalTasks) }}+</div>
                    <div style="font-size:0.75rem;color:rgba(255,255,255,0.5);margin-top:2px;">Tugas Dibuat</div>
                </div>
                <div class="stat-card" style="padding:0.75rem 1.25rem;">
                    <div style="font-size:1.5rem;font-weight:800;color:#34d399;">100%</div>
                    <div style="font-size:0.75rem;color:rgba(255,255,255,0.5);margin-top:2px;">Gratis</div>
                </div>
            </div>
        </div>
        <!-- Mock App Preview -->
        <div class="float-anim">
            <div class="card-glow" style="background:rgba(15,23,42,0.8);border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:1.5rem;max-width:380px;margin:0 auto;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:1.25rem;">
                    <div style="width:12px;height:12px;background:#ef4444;border-radius:50%;"></div>
                    <div style="width:12px;height:12px;background:#f59e0b;border-radius:50%;"></div>
                    <div style="width:12px;height:12px;background:#10b981;border-radius:50%;"></div>
                    <div style="flex:1;background:rgba(255,255,255,0.06);border-radius:6px;padding:4px 10px;font-size:0.7rem;color:rgba(255,255,255,0.4);text-align:center;">TugasKu — Main Project</div>
                </div>
                <div style="font-size:0.7rem;color:rgba(255,255,255,0.4);margin-bottom:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;">📋 Tugas Aktif (3)</div>
                <div class="mock-task"><div class="mock-check"></div><div><div style="font-size:0.85rem;font-weight:600;">Design sistem baru</div><div style="font-size:0.7rem;color:rgba(255,255,255,0.4);">Dibuat 10 Jan 2025</div></div></div>
                <div class="mock-task"><div class="mock-check"></div><div><div style="font-size:0.85rem;font-weight:600;">Review pull request tim</div><div style="font-size:0.7rem;color:rgba(255,255,255,0.4);">Dibuat 11 Jan 2025</div></div></div>
                <div class="mock-task"><div class="mock-check"></div><div><div style="font-size:0.85rem;font-weight:600;">Update dokumentasi API</div><div style="font-size:0.7rem;color:rgba(255,255,255,0.4);">Dibuat 12 Jan 2025</div></div></div>
                <div style="margin:1rem 0;border-top:1px solid rgba(255,255,255,0.08);"></div>
                <div style="font-size:0.7rem;color:rgba(255,255,255,0.4);margin-bottom:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;">✅ Selesai (2)</div>
                <div class="mock-task" style="opacity:0.6;"><div class="mock-check done"><i class="fas fa-check" style="font-size:9px;color:white;"></i></div><div><div style="font-size:0.85rem;font-weight:600;text-decoration:line-through;color:rgba(255,255,255,0.5);">Setup environment dev</div><div style="font-size:0.7rem;color:rgba(255,255,255,0.3);">Selesai 9 Jan 2025</div></div></div>
                <div class="mock-task" style="opacity:0.6;"><div class="mock-check done"><i class="fas fa-check" style="font-size:9px;color:white;"></i></div><div><div style="font-size:0.85rem;font-weight:600;text-decoration:line-through;color:rgba(255,255,255,0.5);">Buat wireframe awal</div><div style="font-size:0.7rem;color:rgba(255,255,255,0.3);">Selesai 8 Jan 2025</div></div></div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section id="fitur" style="padding:6rem 1.5rem;background:#0f172a;">
    <div style="max-width:1100px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:4rem;">
            <div style="display:inline-block;background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);border-radius:50px;padding:6px 18px;font-size:0.8rem;font-weight:700;color:#60a5fa;margin-bottom:1rem;text-transform:uppercase;letter-spacing:0.08em;">Fitur Unggulan</div>
            <h2 style="font-size:2.5rem;font-weight:900;letter-spacing:-0.03em;margin-bottom:1rem;">Semua yang kamu butuhkan,<br><span class="gradient-text">dalam satu tempat.</span></h2>
            <p style="color:rgba(255,255,255,0.55);font-size:1rem;max-width:500px;margin:0 auto;line-height:1.7;">Dirancang khusus untuk yang ingin produktif tanpa ribet. Simpel, cepat, efektif.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;">
            @php
            $features = [
                ['icon'=>'fa-layer-group','color'=>'#60a5fa','title'=>'Multi Proyek','desc'=>'Buat banyak proyek/kategori sesuai kebutuhan. Pisahkan tugas kerja, pribadi, dan hobi dengan rapi.'],
                ['icon'=>'fa-check-circle','color'=>'#34d399','title'=>'Centang & Selesai','desc'=>'Cukup klik centang untuk menandai tugas selesai. Realtime update tanpa perlu refresh halaman.'],
                ['icon'=>'fa-th-large','color'=>'#a78bfa','title'=>'Layout Fleksibel','desc'=>'Tampilkan tugas dalam mode list atau masonry sesuai selera. Sortir ascending maupun descending.'],
                ['icon'=>'fa-mouse-pointer','color'=>'#f472b6','title'=>'Klik untuk Detail','desc'=>'Setiap tugas bisa diklik untuk melihat detail lengkap beserta riwayat tanggal dalam popup yang elegan.'],
                ['icon'=>'fa-undo','color'=>'#fb923c','title'=>'Kembalikan Tugas','desc'=>'Tugas yang sudah selesai bisa dikembalikan ke daftar aktif kapan saja dengan mudah.'],
                ['icon'=>'fa-shield-alt','color'=>'#38bdf8','title'=>'Akun Aman','desc'=>'Setiap pengguna punya data tersendiri. Tidak ada yang bisa melihat tugas orang lain.'],
            ];
            @endphp
            @foreach($features as $f)
            <div class="feature-card" style="border-radius:16px;padding:1.75rem;">
                <div style="width:48px;height:48px;background:rgba(255,255,255,0.07);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                    <i class="fas {{ $f['icon'] }}" style="font-size:1.25rem;color:{{ $f['color'] }};"></i>
                </div>
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.5rem;color:white;">{{ $f['title'] }}</h3>
                <p style="font-size:0.875rem;color:rgba(255,255,255,0.5);line-height:1.65;">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section id="cara-kerja" style="padding:6rem 1.5rem;background:linear-gradient(180deg,#0f172a,#111827);">
    <div style="max-width:900px;margin:0 auto;text-align:center;">
        <div style="display:inline-block;background:rgba(167,139,250,0.15);border:1px solid rgba(167,139,250,0.3);border-radius:50px;padding:6px 18px;font-size:0.8rem;font-weight:700;color:#a78bfa;margin-bottom:1rem;text-transform:uppercase;letter-spacing:0.08em;">Cara Kerja</div>
        <h2 style="font-size:2.5rem;font-weight:900;letter-spacing:-0.03em;margin-bottom:1rem;">Mulai dalam <span class="gradient-text">3 langkah mudah</span></h2>
        <p style="color:rgba(255,255,255,0.55);margin-bottom:4rem;font-size:1rem;">Tidak perlu tutorial panjang. Daftar, buat proyek, dan mulai produktif hari ini.</p>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;text-align:left;">
            @foreach([['1','Daftar Akun','Buat akun gratis dalam 30 detik. Tidak perlu kartu kredit.','#3b82f6'],['2','Buat Proyek','Tambah proyek atau kategori untuk mengorganisir tugasmu.','#6366f1'],['3','Tambah Tugas','Isi judul dan deskripsi, lalu mulai kelola tugasmu!','#8b5cf6']] as $step)
            <div style="position:relative;">
                <div style="width:52px;height:52px;background:linear-gradient(135deg,{{ $step[3] }},{{ $step[3] }}aa);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:900;margin-bottom:1.25rem;color:white;">{{ $step[0] }}</div>
                <h3 style="font-size:1.05rem;font-weight:700;margin-bottom:0.5rem;color:white;">{{ $step[1] }}</h3>
                <p style="font-size:0.875rem;color:rgba(255,255,255,0.5);line-height:1.65;">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>
        <div style="margin-top:3.5rem;">
            <a href="{{ route('register') }}" class="btn-cta" style="font-size:1.1rem;">
                <i class="fas fa-bolt"></i> Coba Sekarang — 100% Gratis
            </a>
        </div>
    </div>
</section>

<!-- FAQ -->
<section id="faq" style="padding:6rem 1.5rem;background:#0f172a;">
    <div style="max-width:700px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:3.5rem;">
            <div style="display:inline-block;background:rgba(52,211,153,0.15);border:1px solid rgba(52,211,153,0.3);border-radius:50px;padding:6px 18px;font-size:0.8rem;font-weight:700;color:#34d399;margin-bottom:1rem;text-transform:uppercase;letter-spacing:0.08em;">FAQ</div>
            <h2 style="font-size:2.25rem;font-weight:900;letter-spacing:-0.03em;color:white;">Pertanyaan Umum</h2>
        </div>
        @foreach([
            ['Apakah TugasKu gratis?','Ya, TugasKu sepenuhnya gratis untuk semua pengguna tanpa batasan fitur.'],
            ['Berapa banyak proyek yang bisa dibuat?','Tidak ada batasan! Kamu bisa membuat sebanyak yang kamu mau.'],
            ['Apakah data saya aman?','Setiap akun terisolasi. Tidak ada pengguna lain yang bisa melihat tugas kamu.'],
            ['Apakah bisa diakses di HP?','Tentu! TugasKu responsif di semua perangkat — desktop, tablet, dan smartphone.'],
        ] as $faq)
        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:1rem;">
            <div style="font-weight:700;color:white;margin-bottom:0.4rem;">{{ $faq[0] }}</div>
            <div style="color:rgba(255,255,255,0.55);font-size:0.9rem;line-height:1.6;">{{ $faq[1] }}</div>
        </div>
        @endforeach
    </div>
</section>

<!-- CTA BOTTOM -->
<section style="padding:6rem 1.5rem;background:linear-gradient(135deg,#1e3a8a,#1d4ed8,#4f46e5);text-align:center;">
    <div style="max-width:600px;margin:0 auto;">
        <h2 style="font-size:2.5rem;font-weight:900;letter-spacing:-0.03em;margin-bottom:1rem;color:white;">Siap mulai produktif?</h2>
        <p style="color:rgba(255,255,255,0.7);font-size:1.05rem;margin-bottom:2.5rem;line-height:1.7;">Bergabung dengan ribuan pengguna yang sudah mengelola tugas lebih baik dengan TugasKu.</p>
        <a href="{{ route('register') }}" class="btn-cta" style="background:white;color:#1d4ed8;font-size:1.05rem;">
            <i class="fas fa-rocket"></i> Daftar Sekarang — Gratis
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer style="background:#0a0f1e;padding:2rem 1.5rem;text-align:center;border-top:1px solid rgba(255,255,255,0.06);">
    <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:0.75rem;">
        <div style="width:28px;height:28px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-check-double" style="color:white;font-size:11px;"></i>
        </div>
        <span style="font-size:1.1rem;font-weight:800;color:white;">TugasKu</span>
    </div>
    <p style="color:rgba(255,255,255,0.3);font-size:0.8rem;">© {{ date('Y') }} TugasKu. Semua hak dilindungi.</p>
</footer>

</body>
</html>
