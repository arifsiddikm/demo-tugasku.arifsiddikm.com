<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="TugasKu Admin Panel">
    <title>@yield('title', 'Admin') — TugasKu Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; }
        .admin-sidebar { background: linear-gradient(180deg, #1e3a8a 0%, #1d4ed8 100%); width: 260px; min-width: 260px; height: 100vh; display: flex; flex-direction: column; }
        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 0.7rem 1.25rem; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.875rem; font-weight: 600; border-left: 3px solid transparent; transition: all 0.2s; }
        .sidebar-link:hover { color: white; background: rgba(255,255,255,0.08); border-left-color: rgba(255,255,255,0.4); }
        .sidebar-link.active { color: white; background: rgba(255,255,255,0.15); border-left-color: white; }
        .card { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
        .form-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; outline: none; transition: all 0.2s; background: #f8fafc; }
        .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); background: white; }
        .form-label { display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.06em; }
        .btn-primary { background: linear-gradient(135deg,#1d4ed8,#3b82f6); color: white; border: none; cursor: pointer; transition: all 0.2s; border-radius: 8px; padding: 0.55rem 1.25rem; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(29,78,216,0.35); }
        .btn-danger { background: linear-gradient(135deg,#dc2626,#ef4444); color: white; border: none; cursor: pointer; transition: all 0.2s; border-radius: 8px; padding: 0.55rem 1rem; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; }
        .btn-danger:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(220,38,38,0.3); }
        .btn-ghost { background: white; border: 1.5px solid #e2e8f0; color: #475569; cursor: pointer; transition: all 0.2s; border-radius: 8px; padding: 0.55rem 1rem; font-weight: 600; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; }
        .btn-ghost:hover { border-color: #3b82f6; color: #3b82f6; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { text-align: left; padding: 0.75rem 1rem; font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; border-bottom: 1.5px solid #f1f5f9; background: #f8fafc; }
        .table td { padding: 0.875rem 1rem; font-size: 0.875rem; color: #334155; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
        .table tr:hover td { background: #fafbff; }
        .badge { display: inline-flex; align-items: center; gap: 4px; font-size: 0.72rem; font-weight: 700; padding: 3px 9px; border-radius: 50px; }
        .badge-active { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-inactive { background: #fff5f5; color: #dc2626; border: 1px solid #fecaca; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.55); backdrop-filter: blur(4px); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .modal-box { background: white; border-radius: 16px; width: 100%; max-width: 480px; box-shadow: 0 25px 60px rgba(0,0,0,0.2); animation: modalIn 0.25s ease; }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .stat-card { background: white; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 4px rgba(0,0,0,0.06); border-left: 4px solid transparent; }
        ::-webkit-scrollbar { width: 5px; } ::-webkit-scrollbar-track { background: #f1f5f9; } ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body>
@if(session('success'))
<script>document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:'success',title:'Berhasil!',text:'{{ session('success') }}',timer:2500,showConfirmButton:false,toast:true,position:'top-end'}));</script>
@endif
@if(session('error'))
<script>document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:'error',title:'Gagal!',text:'{{ session('error') }}',timer:3000,showConfirmButton:false,toast:true,position:'top-end'}));</script>
@endif

<div style="display:flex;height:100vh;overflow:hidden;">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div style="padding:1.5rem 1.25rem;border-bottom:1px solid rgba(255,255,255,0.1);">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;background:rgba(255,255,255,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-check-double" style="color:white;font-size:14px;"></i>
                </div>
                <div>
                    <div style="font-size:1.1rem;font-weight:900;color:white;letter-spacing:-0.02em;">TugasKu</div>
                    <div style="font-size:0.68rem;color:rgba(255,255,255,0.5);font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Admin Panel</div>
                </div>
            </div>
        </div>

        <div style="padding:0.75rem 0;flex:1;">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home" style="width:16px;text-align:center;"></i> Beranda
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users" style="width:16px;text-align:center;"></i> Data Akun Pengguna
            </a>
            <a href="{{ route('admin.admins') }}" class="sidebar-link {{ request()->routeIs('admin.admins') ? 'active' : '' }}">
                <i class="fas fa-user-shield" style="width:16px;text-align:center;"></i> Data Akun Admin
            </a>
        </div>

        <div style="padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,0.1);">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:0.875rem;">
                <div style="width:30px;height:30px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:white;font-size:0.75rem;flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="overflow:hidden;">
                    <div style="font-size:0.8rem;font-weight:700;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.68rem;color:rgba(255,255,255,0.45);">Administrator</div>
                </div>
            </div>
            <button onclick="confirmLogout()" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:0.55rem;background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.25);border-radius:8px;cursor:pointer;color:#fca5a5;font-family:'Plus Jakarta Sans',sans-serif;font-size:0.8rem;font-weight:600;transition:all 0.2s;"
                onmouseover="this.style.background='rgba(239,68,68,0.25)'" onmouseout="this.style.background='rgba(239,68,68,0.15)'">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
        </div>
    </aside>

    <!-- CONTENT -->
    <main style="flex:1;overflow-y:auto;display:flex;flex-direction:column;">
        <div style="background:white;padding:1rem 1.5rem;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;">
            <div>
                <h1 style="font-size:1.1rem;font-weight:800;color:#0f172a;">@yield('page-title', 'Dashboard')</h1>
                <div style="font-size:0.75rem;color:#94a3b8;">@yield('page-subtitle', '')</div>
            </div>
            @yield('header-actions')
        </div>
        <div style="padding:1.5rem;flex:1;">
            @yield('content')
        </div>
    </main>
</div>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Logout Admin?',
        text: 'Kamu yakin ingin keluar dari panel admin?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((r) => { if (r.isConfirmed) document.getElementById('logout-form').submit(); });
}
</script>
@stack('scripts')
</body>
</html>
