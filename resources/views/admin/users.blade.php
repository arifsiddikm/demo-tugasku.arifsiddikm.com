@extends('layouts.admin')
@section('title', 'Data Akun Pengguna')
@section('page-title', 'Data Akun Pengguna')
@section('page-subtitle', 'Monitor dan kelola semua akun pengguna')

@section('header-actions')
<form method="GET" style="display:flex;gap:0.5rem;align-items:center;">
    <div style="position:relative;">
        <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:0.8rem;"></i>
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..."
            style="padding:0.5rem 0.875rem 0.5rem 2.25rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.85rem;font-family:'Plus Jakarta Sans',sans-serif;outline:none;width:240px;transition:all 0.2s;background:#f8fafc;"
            onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
    </div>
    <button type="submit" class="btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;">
        <i class="fas fa-search"></i> Cari
    </button>
    @if($search)
    <a href="{{ route('admin.users') }}" class="btn-ghost" style="padding:0.5rem 0.875rem;font-size:0.85rem;">
        <i class="fas fa-times"></i> Reset
    </a>
    @endif
</form>
@endsection

@section('content')
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <div>
            <h3 style="font-size:0.9rem;font-weight:800;color:#0f172a;">Daftar Pengguna</h3>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">Total: {{ $users->total() }} akun</div>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pengguna</th>
                    <th>Email</th>
                    <th>Proyek</th>
                    <th>Tugas</th>
                    <th>Bergabung</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                <tr id="user-row-{{ $user->id }}">
                    <td style="color:#94a3b8;font-size:0.8rem;">{{ $users->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:34px;height:34px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:700;color:white;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:600;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color:#64748b;font-size:0.85rem;">{{ $user->email }}</td>
                    <td>
                        <span style="background:#f5f3ff;color:#7c3aed;border:1px solid #ddd6fe;font-size:0.72rem;font-weight:700;padding:2px 8px;border-radius:50px;">
                            {{ $user->projects_count }} proyek
                        </span>
                    </td>
                    <td>
                        <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.72rem;font-weight:700;padding:2px 8px;border-radius:50px;">
                            {{ $user->tasks_count }} tugas
                        </span>
                    </td>
                    <td style="color:#64748b;font-size:0.8rem;">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <span id="status-badge-{{ $user->id }}" class="badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                            <i class="fas fa-circle" style="font-size:0.45rem;"></i>
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:5px;">
                            <button onclick="toggleUser({{ $user->id }}, '{{ addslashes($user->name) }}', {{ $user->is_active ? 'true' : 'false' }})"
                                id="toggle-btn-{{ $user->id }}"
                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                style="padding:5px 10px;background:{{ $user->is_active ? '#fff7ed' : '#f0fdf4' }};border:1px solid {{ $user->is_active ? '#fed7aa' : '#bbf7d0' }};border-radius:6px;cursor:pointer;font-size:0.78rem;font-weight:600;color:{{ $user->is_active ? '#ea580c' : '#16a34a' }};transition:all 0.2s;font-family:'Plus Jakarta Sans',sans-serif;">
                                <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                            <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                style="padding:5px 8px;background:#fff5f5;border:1px solid #fecaca;border-radius:6px;cursor:pointer;color:#ef4444;transition:all 0.2s;font-size:0.78rem;"
                                title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:#94a3b8;">
                        <i class="fas fa-users" style="font-size:2rem;margin-bottom:0.75rem;display:block;opacity:0.3;"></i>
                        {{ $search ? 'Pengguna tidak ditemukan.' : 'Belum ada pengguna terdaftar.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="margin-top:1.25rem;display:flex;justify-content:center;">
        {{ $users->appends(['search' => $search])->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

async function toggleUser(id, name, isActive) {
    const action = isActive ? 'nonaktifkan' : 'aktifkan';
    const result = await Swal.fire({
        title: `${isActive ? 'Nonaktifkan' : 'Aktifkan'} Pengguna?`,
        text: `Akun "${name}" akan di${action}.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: isActive ? '#ef4444' : '#22c55e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `Ya, ${action.charAt(0).toUpperCase() + action.slice(1)}`,
        cancelButtonText: 'Batal'
    });
    if (!result.isConfirmed) return;

    const res = await fetch(`/webmin/users/${id}/toggle`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF } });
    const data = await res.json();
    if (data.success) {
        const newActive = data.is_active;
        const badge = document.getElementById(`status-badge-${id}`);
        const btn = document.getElementById(`toggle-btn-${id}`);
        badge.className = `badge ${newActive ? 'badge-active' : 'badge-inactive'}`;
        badge.innerHTML = `<i class="fas fa-circle" style="font-size:0.45rem;"></i> ${newActive ? 'Aktif' : 'Nonaktif'}`;
        btn.innerHTML = `<i class="fas ${newActive ? 'fa-ban' : 'fa-check'}"></i> ${newActive ? 'Nonaktifkan' : 'Aktifkan'}`;
        btn.style.cssText = `padding:5px 10px;background:${newActive ? '#fff7ed' : '#f0fdf4'};border:1px solid ${newActive ? '#fed7aa' : '#bbf7d0'};border-radius:6px;cursor:pointer;font-size:0.78rem;font-weight:600;color:${newActive ? '#ea580c' : '#16a34a'};transition:all 0.2s;font-family:'Plus Jakarta Sans',sans-serif;`;
        btn.onclick = () => toggleUser(id, name, newActive);
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: `Akun "${name}" berhasil di${action}.`, timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
    }
}

async function deleteUser(id, name) {
    const result = await Swal.fire({
        title: 'Hapus Pengguna?',
        html: `Akun <strong>"${name}"</strong> dan semua datanya akan dihapus permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
    if (!result.isConfirmed) return;
    const res = await fetch(`/webmin/users/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF } });
    const data = await res.json();
    if (data.success) {
        document.getElementById(`user-row-${id}`)?.remove();
        Swal.fire({ icon: 'success', title: 'Terhapus!', text: `Akun "${name}" berhasil dihapus.`, timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
    }
}
</script>
@endpush
@endsection
