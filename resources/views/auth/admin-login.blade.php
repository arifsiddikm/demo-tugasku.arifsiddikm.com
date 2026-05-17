<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — TugasKu</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .auth-card { background: white; border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 30px 80px rgba(0,0,0,0.3); }
        .form-group { margin-bottom: 1.15rem; }
        .form-label { display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.06em; }
        .form-input { width: 100%; padding: 0.7rem 1rem 0.7rem 2.5rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; outline: none; transition: all 0.2s; background: #f8fafc; }
        .form-input:focus { border-color: #1d4ed8; box-shadow: 0 0 0 3px rgba(29,78,216,0.12); background: white; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; }
        .btn-login { width: 100%; padding: 0.8rem; background: linear-gradient(135deg,#1e3a8a,#2563eb); color: white; border: none; border-radius: 10px; font-size: 0.95rem; font-weight: 700; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s; }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(29,78,216,0.4); }
        .error-msg { font-size: 0.78rem; color: #ef4444; margin-top: 0.3rem; }
        .autofill-btn { width: 100%; padding: 0.65rem; background: #eff6ff; border: 1.5px dashed #bfdbfe; border-radius: 10px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; color: #1d4ed8; transition: all 0.2s; margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .autofill-btn:hover { background: #dbeafe; border-color: #93c5fd; }
    </style>
</head>
<body>
@if(session('error'))
<script>document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:'error',title:'Akses Ditolak',text:'{{ session('error') }}',confirmButtonColor:'#1d4ed8'}));</script>
@endif

<div style="width:100%;max-width:420px;">
    <div style="text-align:center;margin-bottom:1.75rem;">
        <div style="display:inline-flex;align-items:center;gap:10px;">
            <div style="width:44px;height:44px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-check-double" style="color:white;font-size:18px;"></i>
            </div>
            <div style="text-align:left;">
                <div style="font-size:1.4rem;font-weight:900;color:white;letter-spacing:-0.03em;">TugasKu</div>
                <div style="font-size:0.72rem;color:rgba(255,255,255,0.6);font-weight:700;text-transform:uppercase;letter-spacing:0.08em;">Admin Panel</div>
            </div>
        </div>
    </div>

    <div class="auth-card">
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:56px;height:56px;background:linear-gradient(135deg,#1e3a8a,#2563eb);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="fas fa-user-shield" style="color:white;font-size:22px;"></i>
            </div>
            <h1 style="font-size:1.35rem;font-weight:800;color:#0f172a;margin-bottom:0.25rem;">Login Admin</h1>
            <p style="color:#94a3b8;font-size:0.85rem;">Masuk ke panel administrator</p>
        </div>

        <!-- Autofill button -->
        <button type="button" class="autofill-btn" onclick="autofillAdmin()">
            <i class="fas fa-magic"></i> Autofill Akun Admin Testing
        </button>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Admin</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" id="admin-email" class="form-input {{ $errors->has('email') ? '' : '' }}"
                        placeholder="admin@tugasku.com" value="{{ old('email') }}" required>
                </div>
                @error('email') <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="form-label">Password</label>
                <div class="input-wrap" style="position:relative;">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="admin-password" class="form-input"
                        placeholder="••••••••" required style="padding-right:2.75rem;">
                    <button type="button" onclick="togglePwd()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;">
                        <i class="fas fa-eye" id="eye-icon"></i>
                    </button>
                </div>
                @error('password') <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt" style="margin-right:6px;"></i> Masuk ke Admin Panel
            </button>
        </form>

        <div style="margin-top:1.25rem;text-align:center;">
            <a href="{{ route('landing') }}" style="font-size:0.8rem;color:#94a3b8;text-decoration:none;">
                <i class="fas fa-arrow-left" style="margin-right:4px;"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
function autofillAdmin() {
    document.getElementById('admin-email').value = 'admin@tugasku.com';
    document.getElementById('admin-password').value = 'admin123';
    Swal.fire({ icon: 'info', title: 'Data Diisi!', text: 'Silakan klik tombol Login untuk masuk.', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
}
function togglePwd() {
    const i = document.getElementById('admin-password');
    const e = document.getElementById('eye-icon');
    if (i.type === 'password') { i.type = 'text'; e.className = 'fas fa-eye-slash'; }
    else { i.type = 'password'; e.className = 'fas fa-eye'; }
}
</script>
</body>
</html>
