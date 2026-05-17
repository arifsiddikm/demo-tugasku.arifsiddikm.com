@extends('layouts.admin')
@section('title', 'Data Akun Admin')
@section('page-title', 'Data Akun Admin')
@section('page-subtitle', 'Kelola akun administrator sistem')

@section('header-actions')
<button onclick="document.getElementById('add-admin-modal').style.display='flex'" class="btn-primary" style="display:flex;align-items:center;gap:6px;font-size:0.875rem;">
    <i class="fas fa-plus"></i> Tambah Admin
</button>
@endsection

@section('content')
<!-- Search -->
<div style="margin-bottom:1.25rem;">
    <form method="GET" style="display:flex;gap:0.5rem;">
        <div style="position:relative;flex:1;max-width:300px;">
            <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:0.8rem;"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari admin..."
                style="width:100%;padding:0.5rem 0.875rem 0.5rem 2.25rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.85rem;font-family:'Plus Jakarta Sans',sans-serif;outline:none;background:#f8fafc;"
                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
        </div>
        <button type="submit" class="btn-primary" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Cari</button>
        @if($search)<a href="{{ route('admin.admins') }}" class="btn-ghost" style="padding:0.5rem 0.875rem;font-size:0.85rem;text-decoration:none;display:flex;align-items:center;"><i class="fas fa-times"></i></a>@endif
    </form>
</div>

<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <div>
            <h3 style="font-size:0.9rem;font-weight:800;color:#0f172a;">Daftar Admin</h3>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">Total: {{ $admins->total() }} admin</div>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Admin</th>
                    <th>Email</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $i => $admin)
                <tr id="admin-row-{{ $admin->id }}">
                    <td style="color:#94a3b8;font-size:0.8rem;">{{ $admins->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:34px;height:34px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:700;color:white;flex-shrink:0;">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;">{{ $admin->name }}</div>
                                @if($admin->id === auth()->id())
                                <span style="font-size:0.68rem;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:1px 6px;border-radius:50px;font-weight:700;">Anda</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="color:#64748b;font-size:0.85rem;">{{ $admin->email }}</td>
                    <td style="color:#64748b;font-size:0.8rem;">{{ $admin->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:5px;">
                            <button onclick="openEditAdmin({{ $admin->id }}, '{{ addslashes($admin->name) }}', '{{ $admin->email }}')"
                                style="padding:5px 10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;cursor:pointer;font-size:0.78rem;font-weight:600;color:#1d4ed8;transition:all 0.2s;font-family:'Plus Jakarta Sans',sans-serif;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            @if($admin->id !== auth()->id())
                            <button onclick="deleteAdmin({{ $admin->id }}, '{{ addslashes($admin->name) }}')"
                                style="padding:5px 8px;background:#fff5f5;border:1px solid #fecaca;border-radius:6px;cursor:pointer;color:#ef4444;transition:all 0.2s;font-size:0.78rem;">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:3rem;color:#94a3b8;">Belum ada admin.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($admins->hasPages())
    <div style="margin-top:1.25rem;display:flex;justify-content:center;">
        {{ $admins->appends(['search' => $search])->links() }}
    </div>
    @endif
</div>

<!-- ADD ADMIN MODAL -->
<div id="add-admin-modal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-box">
        <div style="padding:1.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;"><i class="fas fa-user-plus" style="color:#1d4ed8;margin-right:8px;"></i>Tambah Admin Baru</h3>
                <button onclick="document.getElementById('add-admin-modal').style.display='none'" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:1.1rem;"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.admins.store') }}">
                @csrf
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" placeholder="Nama admin" required>
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="email@example.com" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Min. 6 karakter" required>
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                    </div>
                </div>
                <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                    <button type="button" onclick="document.getElementById('add-admin-modal').style.display='none'" class="btn-ghost">Batal</button>
                    <button type="submit" class="btn-primary"><i class="fas fa-plus"></i> Tambah Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT ADMIN MODAL -->
<div id="edit-admin-modal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-box">
        <div style="padding:1.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;"><i class="fas fa-edit" style="color:#1d4ed8;margin-right:8px;"></i>Edit Admin</h3>
                <button onclick="document.getElementById('edit-admin-modal').style.display='none'" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:1.1rem;"><i class="fas fa-times"></i></button>
            </div>
            <form id="edit-admin-form" method="POST">
                @csrf @method('PUT')
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="edit-admin-name" class="form-input" required>
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit-admin-email" class="form-input" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:0.5rem;">
                    <div>
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak ubah">
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Konfirmasi password">
                    </div>
                </div>
                <p style="font-size:0.75rem;color:#94a3b8;margin-bottom:1.5rem;">Kosongkan password jika tidak ingin mengubahnya.</p>
                <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                    <button type="button" onclick="document.getElementById('edit-admin-modal').style.display='none'" class="btn-ghost">Batal</button>
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function openEditAdmin(id, name, email) {
    document.getElementById('edit-admin-name').value = name;
    document.getElementById('edit-admin-email').value = email;
    document.getElementById('edit-admin-form').action = `/webmin/admins/${id}`;
    document.getElementById('edit-admin-modal').style.display = 'flex';
}

async function deleteAdmin(id, name) {
    const result = await Swal.fire({
        title: 'Hapus Admin?',
        html: `Admin <strong>"${name}"</strong> akan dihapus permanen.`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
    });
    if (!result.isConfirmed) return;
    const res = await fetch(`/webmin/admins/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF } });
    const data = await res.json();
    if (data.success) {
        document.getElementById(`admin-row-${id}`)?.remove();
        Swal.fire({ icon: 'success', title: 'Terhapus!', text: `Admin "${name}" berhasil dihapus.`, timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
    } else {
        Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message });
    }
}
</script>
@endpush
@endsection
