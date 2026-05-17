@extends('layouts.admin')
@section('title', 'Beranda Admin')
@section('page-title', 'Beranda')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-bottom:1.5rem;">
    <div class="stat-card" style="border-left-color:#3b82f6;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
            <div style="font-size:0.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">Total Pengguna</div>
            <div style="width:40px;height:40px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-users" style="color:#3b82f6;font-size:1rem;"></i>
            </div>
        </div>
        <div style="font-size:2rem;font-weight:900;color:#0f172a;line-height:1;">{{ $totalUsers }}</div>
        <div style="font-size:0.78rem;color:#94a3b8;margin-top:4px;">Akun pengguna terdaftar</div>
    </div>

    <div class="stat-card" style="border-left-color:#8b5cf6;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
            <div style="font-size:0.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">Total Proyek</div>
            <div style="width:40px;height:40px;background:#f5f3ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-layer-group" style="color:#8b5cf6;font-size:1rem;"></i>
            </div>
        </div>
        <div style="font-size:2rem;font-weight:900;color:#0f172a;line-height:1;">{{ $totalProjects }}</div>
        <div style="font-size:0.78rem;color:#94a3b8;margin-top:4px;">Proyek/kategori dibuat</div>
    </div>

    <div class="stat-card" style="border-left-color:#22c55e;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
            <div style="font-size:0.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">Tugas Selesai</div>
            <div style="width:40px;height:40px;background:#f0fdf4;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-check-circle" style="color:#22c55e;font-size:1rem;"></i>
            </div>
        </div>
        <div style="font-size:2rem;font-weight:900;color:#0f172a;line-height:1;">{{ $doneTasks }}</div>
        <div style="font-size:0.78rem;color:#94a3b8;margin-top:4px;">dari {{ $totalTasks }} total tugas</div>
    </div>

    <div class="stat-card" style="border-left-color:#f59e0b;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
            <div style="font-size:0.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">Tugas Aktif</div>
            <div style="width:40px;height:40px;background:#fffbeb;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-tasks" style="color:#f59e0b;font-size:1rem;"></i>
            </div>
        </div>
        <div style="font-size:2rem;font-weight:900;color:#0f172a;line-height:1;">{{ $todoTasks }}</div>
        <div style="font-size:0.78rem;color:#94a3b8;margin-top:4px;">Sedang dikerjakan pengguna</div>
    </div>

    <div class="stat-card" style="border-left-color:#ef4444;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
            <div style="font-size:0.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">Akun Admin</div>
            <div style="width:40px;height:40px;background:#fff5f5;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user-shield" style="color:#ef4444;font-size:1rem;"></i>
            </div>
        </div>
        <div style="font-size:2rem;font-weight:900;color:#0f172a;line-height:1;">{{ $totalAdmins }}</div>
        <div style="font-size:0.78rem;color:#94a3b8;margin-top:4px;">Administrator aktif</div>
    </div>

    <div class="stat-card" style="border-left-color:#06b6d4;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
            <div style="font-size:0.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">Tingkat Selesai</div>
            <div style="width:40px;height:40px;background:#ecfeff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-chart-pie" style="color:#06b6d4;font-size:1rem;"></i>
            </div>
        </div>
        <div style="font-size:2rem;font-weight:900;color:#0f172a;line-height:1;">{{ $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0 }}%</div>
        <div style="font-size:0.78rem;color:#94a3b8;margin-top:4px;">Persentase tugas selesai</div>
    </div>
</div>

<!-- Progress bar -->
@if($totalTasks > 0)
<div class="card" style="margin-bottom:1.5rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
        <div style="font-size:0.875rem;font-weight:700;color:#0f172a;">Progress Tugas Keseluruhan</div>
        <div style="font-size:0.875rem;font-weight:700;color:#22c55e;">{{ round(($doneTasks / $totalTasks) * 100) }}%</div>
    </div>
    <div style="height:10px;background:#f1f5f9;border-radius:10px;overflow:hidden;">
        <div style="height:100%;width:{{ round(($doneTasks / $totalTasks) * 100) }}%;background:linear-gradient(90deg,#22c55e,#16a34a);border-radius:10px;transition:width 0.5s ease;"></div>
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:0.5rem;font-size:0.75rem;color:#94a3b8;">
        <span>{{ $doneTasks }} selesai</span>
        <span>{{ $todoTasks }} aktif</span>
    </div>
</div>
@endif

<!-- Recent Users -->
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <h3 style="font-size:0.9rem;font-weight:800;color:#0f172a;">Pengguna Terbaru</h3>
        <a href="{{ route('admin.users') }}" style="font-size:0.8rem;color:#3b82f6;text-decoration:none;font-weight:600;">Lihat semua →</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Bergabung</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentUsers as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:white;flex-shrink:0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span style="font-weight:600;">{{ $user->name }}</span>
                    </div>
                </td>
                <td style="color:#64748b;">{{ $user->email }}</td>
                <td style="color:#64748b;font-size:0.8rem;">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <span class="badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                        <i class="fas fa-circle" style="font-size:0.45rem;"></i>
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:2rem;">Belum ada pengguna</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
