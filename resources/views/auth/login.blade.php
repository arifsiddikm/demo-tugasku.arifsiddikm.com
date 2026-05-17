<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke TugasKu - Kelola tugas dan produktivitas harianmu">
    <title>Masuk — TugasKu</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .auth-card { background: white; border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 440px; box-shadow: 0 30px 80px rgba(0,0,0,0.4); }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.06em; }
        .form-input { width: 100%; padding: 0.7rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; outline: none; transition: all 0.2s ease; background: #f8fafc; }
        .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); background: white; }
        .form-input.error { border-color: #ef4444; background: #fff5f5; }
        .btn-login { width: 100%; padding: 0.8rem; background: linear-gradient(135deg,#3b5bdb,#4c6ef5); color: white; border: none; border-radius: 10px; font-size: 0.95rem; font-weight: 700; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s ease; margin-top: 0.5rem; }
        .btn-login:hover { background: linear-gradient(135deg,#364fc7,#4361ee); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(76,110,245,0.4); }
        .input-icon-wrapper { position: relative; }
        .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; }
        .input-icon-wrapper .form-input { padding-left: 2.5rem; }
        .error-msg { font-size: 0.78rem; color: #ef4444; margin-top: 0.3rem; }
        .divider { display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0; }
        .divider::before, .divider::after { content: ''; flex: 1; border-top: 1px solid #e2e8f0; }
        .divider span { font-size: 0.78rem; color: #94a3b8; font-weight: 500; }
        .checkbox-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; color: #475569; }
        .checkbox-label input[type="checkbox"] { width: 16px; height: 16px; accent-color: #3b82f6; cursor: pointer; }

        /* Demo autofill buttons */
        .demo-section { background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; }
        .demo-section-title { font-size: 0.72rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.625rem; display: flex; align-items: center; gap: 6px; }
        .demo-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }
        .btn-demo { display: flex; align-items: center; gap: 8px; padding: 0.55rem 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 8px; background: white; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; font-weight: 600; color: #374151; transition: all 0.15s ease; text-align: left; width: 100%; }
        .btn-demo:hover { border-color: #3b82f6; color: #1d4ed8; background: #eff6ff; transform: translateY(-1px); box-shadow: 0 2px 8px rgba(59,130,246,0.15); }
        .btn-demo .demo-avatar { width: 26px; height: 26px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: white; flex-shrink: 0; }
        .btn-demo .demo-info { overflow: hidden; }
        .btn-demo .demo-name { font-size: 0.8rem; font-weight: 700; color: inherit; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .btn-demo .demo-role { font-size: 0.68rem; color: #94a3b8; font-weight: 500; }
        .btn-demo-admin { grid-column: 1 / -1; border-color: #fde68a; background: #fffbeb; }
        .btn-demo-admin:hover { border-color: #f59e0b; background: #fffbeb; color: #92400e; }
    </style>
</head>
<body>
@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon: 'error', title: 'Akses Ditolak', text: '{{ session('error') }}', confirmButtonColor: '#3b82f6' });
});
</script>
@endif

<div style="width:100%;max-width:440px;">
    <!-- Logo -->
    <div style="text-align:center;margin-bottom:1.75rem;">
        <a href="{{ route('landing') }}" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-check-double" style="color:white;font-size:18px;"></i>
            </div>
            <span style="font-size:1.5rem;font-weight:900;color:white;letter-spacing:-0.03em;">TugasKu</span>
        </a>
    </div>

    <div class="auth-card">
        <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;margin-bottom:0.25rem;text-align:center;">Selamat Datang Kembali</h1>
        <p style="color:#94a3b8;font-size:0.875rem;text-align:center;margin-bottom:1.5rem;">Masuk untuk melanjutkan tugasmu</p>

        <!-- ── DEMO AUTOFILL ── -->
        <div class="demo-section">
            <div class="demo-section-title">
                <i class="fas fa-bolt" style="color:#f59e0b;"></i>
                Coba Demo — Klik untuk autofill
            </div>
            <div class="demo-grid">
                <!-- Admin -->
                <button type="button" class="btn-demo btn-demo-admin" onclick="autofill('admin@tugasku.com','admin123')">
                    <div class="demo-avatar" style="background:linear-gradient(135deg,#f59e0b,#d97706);">A</div>
                    <div class="demo-info">
                        <div class="demo-name">Administrator</div>
                        <div class="demo-role">⭐ Admin Panel</div>
                    </div>
                </button>
                <!-- Demo User -->
                <button type="button" class="btn-demo" onclick="autofill('demo@tugasku.com','demo123')">
                    <div class="demo-avatar" style="background:linear-gradient(135deg,#3b82f6,#6366f1);">B</div>
                    <div class="demo-info">
                        <div class="demo-name">Budi Santoso</div>
                        <div class="demo-role">Developer</div>
                    </div>
                </button>
                <!-- Sari -->
                <button type="button" class="btn-demo" onclick="autofill('sari@tugasku.com','sari123')">
                    <div class="demo-avatar" style="background:linear-gradient(135deg,#ec4899,#db2777);">S</div>
                    <div class="demo-info">
                        <div class="demo-name">Sari Dewi</div>
                        <div class="demo-role">UI/UX Designer</div>
                    </div>
                </button>
                <!-- Reza -->
                <button type="button" class="btn-demo" onclick="autofill('reza@tugasku.com','reza123')">
                    <div class="demo-avatar" style="background:linear-gradient(135deg,#10b981,#059669);">R</div>
                    <div class="demo-info">
                        <div class="demo-name">Reza Firmansyah</div>
                        <div class="demo-role">Project Manager</div>
                    </div>
                </button>
            </div>
        </div>
        <!-- ── END DEMO AUTOFILL ── -->

        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" id="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                        placeholder="nama@email.com" value="{{ old('email') }}" required autocomplete="email">
                </div>
                @error('email') <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-icon-wrapper" style="position:relative;">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                        placeholder="••••••••" required autocomplete="current-password" style="padding-right:2.75rem;">
                    <button type="button" onclick="togglePassword()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;">
                        <i class="fas fa-eye" id="eye-icon"></i>
                    </button>
                </div>
                @error('password') <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt" style="margin-right:6px;"></i> Masuk ke TugasKu
            </button>
        </form>

        <div class="divider"><span>atau</span></div>

        <p style="text-align:center;font-size:0.875rem;color:#64748b;">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color:#3b82f6;font-weight:700;text-decoration:none;">Daftar Gratis</a>
        </p>
    </div>

    <p style="text-align:center;margin-top:1.5rem;font-size:0.78rem;color:rgba(255,255,255,0.3);">
        © {{ date('Y') }} TugasKu. <a href="{{ route('landing') }}" style="color:rgba(255,255,255,0.4);text-decoration:none;">Kembali ke Beranda</a>
    </p>
</div>

<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (pwd.type === 'password') { pwd.type = 'text'; icon.className = 'fas fa-eye-slash'; }
    else { pwd.type = 'password'; icon.className = 'fas fa-eye'; }
}

function autofill(email, password) {
    const emailInput = document.getElementById('email');
    const passInput  = document.getElementById('password');

    // Animate fill
    emailInput.style.borderColor = '#3b82f6';
    emailInput.style.boxShadow   = '0 0 0 3px rgba(59,130,246,0.12)';
    emailInput.value = email;

    passInput.style.borderColor = '#3b82f6';
    passInput.style.boxShadow   = '0 0 0 3px rgba(59,130,246,0.12)';
    passInput.value = password;
    passInput.type  = 'text';
    document.getElementById('eye-icon').className = 'fas fa-eye-slash';

    setTimeout(() => {
        emailInput.style.borderColor = '#e2e8f0';
        emailInput.style.boxShadow   = '';
        passInput.style.borderColor  = '#e2e8f0';
        passInput.style.boxShadow    = '';
    }, 1200);
}
</script>
</body>
</html>
