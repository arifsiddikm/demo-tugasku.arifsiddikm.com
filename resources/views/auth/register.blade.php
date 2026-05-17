<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daftar TugasKu gratis - Mulai kelola tugasmu hari ini">
    <title>Daftar Akun — TugasKu</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .auth-card { background: white; border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 460px; box-shadow: 0 30px 80px rgba(0,0,0,0.4); }
        .form-group { margin-bottom: 1.15rem; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.06em; }
        .form-input { width: 100%; padding: 0.7rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; outline: none; transition: all 0.2s ease; background: #f8fafc; }
        .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); background: white; }
        .form-input.error { border-color: #ef4444; background: #fff5f5; }
        .input-icon-wrapper { position: relative; }
        .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; }
        .input-icon-wrapper .form-input { padding-left: 2.5rem; }
        .btn-register { width: 100%; padding: 0.8rem; background: linear-gradient(135deg,#3b5bdb,#4c6ef5); color: white; border: none; border-radius: 10px; font-size: 0.95rem; font-weight: 700; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s ease; margin-top: 0.5rem; }
        .btn-register:hover { background: linear-gradient(135deg,#364fc7,#4361ee); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(76,110,245,0.4); }
        .error-msg { font-size: 0.78rem; color: #ef4444; margin-top: 0.3rem; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    </style>
</head>
<body>
<div style="width:100%;max-width:460px;">
    <div style="text-align:center;margin-bottom:1.75rem;">
        <a href="{{ route('landing') }}" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-check-double" style="color:white;font-size:18px;"></i>
            </div>
            <span style="font-size:1.5rem;font-weight:900;color:white;letter-spacing:-0.03em;">TugasKu</span>
        </a>
    </div>

    <div class="auth-card">
        <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;margin-bottom:0.25rem;text-align:center;">Buat Akun Gratis</h1>
        <p style="color:#94a3b8;font-size:0.875rem;text-align:center;margin-bottom:2rem;">Mulai kelola tugasmu dalam hitungan detik</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                        placeholder="Nama kamu" value="{{ old('name') }}" required autocomplete="name">
                </div>
                @error('name') <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                        placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>
                @error('email') <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" id="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                            placeholder="Min. 6 karakter" required>
                    </div>
                    @error('password') <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" class="form-input"
                            placeholder="Ulangi password" required>
                    </div>
                </div>
            </div>

            <!-- Password strength -->
            <div id="pwd-strength" style="margin-bottom:1rem;display:none;">
                <div style="height:4px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                    <div id="strength-bar" style="height:100%;width:0;transition:all 0.3s;border-radius:4px;"></div>
                </div>
                <div id="strength-text" style="font-size:0.72rem;margin-top:4px;color:#94a3b8;"></div>
            </div>

            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus" style="margin-right:6px;"></i> Daftar Sekarang
            </button>
        </form>

        <div style="margin-top:1.5rem;text-align:center;">
            <p style="font-size:0.875rem;color:#64748b;">
                Sudah punya akun?
                <a href="{{ route('login') }}" style="color:#3b82f6;font-weight:700;text-decoration:none;">Masuk</a>
            </p>
        </div>
    </div>

    <p style="text-align:center;margin-top:1.5rem;font-size:0.78rem;color:rgba(255,255,255,0.3);">
        © {{ date('Y') }} TugasKu. <a href="{{ route('landing') }}" style="color:rgba(255,255,255,0.4);text-decoration:none;">Kembali ke Beranda</a>
    </p>
</div>

<script>
const pwd = document.getElementById('password');
const bar = document.getElementById('strength-bar');
const text = document.getElementById('strength-text');
const wrap = document.getElementById('pwd-strength');

pwd.addEventListener('input', function() {
    const v = this.value;
    if (!v) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'block';
    let score = 0;
    if (v.length >= 6) score++;
    if (v.length >= 10) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^a-zA-Z0-9]/.test(v)) score++;
    const labels = ['','Sangat Lemah','Lemah','Sedang','Kuat','Sangat Kuat'];
    const colors = ['','#ef4444','#f97316','#eab308','#22c55e','#16a34a'];
    bar.style.width = (score * 20) + '%';
    bar.style.background = colors[score] || '#e2e8f0';
    text.textContent = labels[score] || '';
    text.style.color = colors[score];
});
</script>
</body>
</html>
