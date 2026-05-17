<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="TugasKu - Aplikasi manajemen tugas modern untuk produktivitas maksimal">
    <meta name="keywords" content="todo, task manager, tugas, produktivitas, TugasKu">
    <meta name="author" content="TugasKu">
    <meta property="og:title" content="@yield('title', 'TugasKu') - Kelola Tugasmu">
    <meta property="og:description" content="Aplikasi manajemen tugas sederhana dan modern">
    <meta property="og:type" content="website">
    <title>@yield('title', 'Dashboard') — TugasKu</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50: '#f0f4ff', 100: '#dbe4ff', 200: '#bac8ff',
                            300: '#91a7ff', 400: '#748ffc', 500: '#5c7cfa',
                            600: '#4c6ef5', 700: '#3b5bdb', 800: '#364fc7',
                            900: '#2f44b0', 950: '#1e3a8a',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        .sidebar-transition { transition: transform 0.3s ease, width 0.3s ease; }
        .task-item { transition: all 0.2s ease; }
        .task-item:hover { transform: translateY(-1px); }
        .btn-primary {
            background: linear-gradient(135deg, #3b5bdb, #4c6ef5);
            color: white; border: none; cursor: pointer;
            transition: all 0.2s ease; border-radius: 8px;
            padding: 0.5rem 1.25rem; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-primary:hover { background: linear-gradient(135deg, #364fc7, #4361ee); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(76,110,245,0.35); }
        .btn-danger { background: linear-gradient(135deg, #e03131, #f03e3e); color: white; border: none; cursor: pointer; transition: all 0.2s ease; border-radius: 8px; padding: 0.5rem 1.25rem; font-weight: 600; font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-danger:hover { background: linear-gradient(135deg, #c92a2a, #e03131); transform: translateY(-1px); }
        .btn-ghost { background: transparent; border: 1.5px solid #e2e8f0; color: #475569; cursor: pointer; transition: all 0.2s ease; border-radius: 8px; padding: 0.5rem 1.25rem; font-weight: 500; font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-ghost:hover { background: #f8fafc; border-color: #cbd5e1; }
        .form-input {
            width: 100%; padding: 0.625rem 0.875rem; border: 1.5px solid #e2e8f0;
            border-radius: 8px; font-size: 0.9rem; transition: all 0.2s ease;
            font-family: 'Plus Jakarta Sans', sans-serif; background: #fff; color: #1e293b;
            outline: none;
        }
        .form-input:focus { border-color: #4c6ef5; box-shadow: 0 0 0 3px rgba(76,110,245,0.12); }
        .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .form-textarea { width: 100%; padding: 0.625rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; transition: all 0.2s ease; font-family: 'Plus Jakarta Sans', sans-serif; background: #fff; color: #1e293b; outline: none; resize: vertical; min-height: 80px; }
        .form-textarea:focus { border-color: #4c6ef5; box-shadow: 0 0 0 3px rgba(76,110,245,0.12); }
        .badge-todo { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-done { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.55); backdrop-filter: blur(4px); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .modal-box { background: white; border-radius: 16px; width: 100%; max-width: 520px; box-shadow: 0 25px 60px rgba(0,0,0,0.2); animation: modalIn 0.25s ease; }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .sidebar-active { background: linear-gradient(135deg, rgba(76,110,245,0.12), rgba(91,107,250,0.08)); border-left: 3px solid #4c6ef5; color: #3b5bdb; }
        .checkbox-custom { width: 18px; height: 18px; border: 2px solid #cbd5e1; border-radius: 50%; cursor: pointer; transition: all 0.2s ease; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .checkbox-custom:hover { border-color: #4c6ef5; }
        .checkbox-custom.checked { background: #4c6ef5; border-color: #4c6ef5; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadein { animation: fadeIn 0.3s ease forwards; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800">
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
});
</script>
@endif
@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
});
</script>
@endif
@yield('content')
@stack('scripts')
</body>
</html>
