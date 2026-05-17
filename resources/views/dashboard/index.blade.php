@extends('layouts.app')
@section('title', $currentProject->name ?? 'Dashboard')

@section('content')
<div style="display:flex;height:100vh;overflow:hidden;background:#f8fafc;">

    <!-- SIDEBAR -->
    <aside id="sidebar" style="width:280px;min-width:280px;background:#0f172a;display:flex;flex-direction:column;height:100vh;overflow:hidden;transition:all 0.3s ease;">
        <!-- Logo -->
        <div style="padding:1.25rem 1.25rem 1rem;border-bottom:1px solid rgba(255,255,255,0.08);">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <a href="{{ route('landing') }}" style="display:flex;align-items:center;gap:9px;text-decoration:none;">
                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:9px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-check-double" style="color:white;font-size:12px;"></i>
                    </div>
                    <span style="font-size:1.2rem;font-weight:900;color:white;letter-spacing:-0.03em;">TugasKu</span>
                </a>
                <div style="width:8px;height:8px;background:#22c55e;border-radius:50%;box-shadow:0 0 6px #22c55e;"></div>
            </div>
        </div>

        <!-- User info -->
        <div style="padding:1rem 1.25rem;border-bottom:1px solid rgba(255,255,255,0.06);">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:white;font-size:0.85rem;flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="overflow:hidden;">
                    <div style="font-size:0.85rem;font-weight:700;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.72rem;color:rgba(255,255,255,0.4);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->email }}</div>
                </div>
            </div>
        </div>

        <!-- Projects list -->
        <div style="flex:1;overflow-y:auto;padding:0.75rem 0;">
            <div style="padding:0 1.25rem;margin-bottom:0.5rem;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:0.7rem;font-weight:700;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:0.08em;">Proyek & Kategori</span>
                <button onclick="openAddProject()" style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.4);padding:2px;transition:color 0.2s;" title="Tambah Proyek" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                    <i class="fas fa-plus" style="font-size:0.8rem;"></i>
                </button>
            </div>

            <div id="projects-list">
                @foreach($projects as $project)
                <div class="project-item" data-id="{{ $project->id }}" onclick="window.location='{{ route('dashboard.project', $project->id) }}'"
                    style="display:flex;align-items:center;gap:10px;padding:0.6rem 1.25rem;cursor:pointer;transition:all 0.15s;border-left:3px solid transparent;
                    {{ $currentProject->id === $project->id ? 'background:rgba(59,130,246,0.12);border-left-color:#3b82f6;' : '' }}">
                    <span style="width:10px;height:10px;background:{{ $project->color }};border-radius:50%;flex-shrink:0;"></span>
                    <span style="flex:1;font-size:0.875rem;font-weight:{{ $currentProject->id === $project->id ? '700' : '500' }};
                        color:{{ $currentProject->id === $project->id ? 'white' : 'rgba(255,255,255,0.65)' }};
                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $project->name }}</span>
                    <span style="font-size:0.7rem;color:rgba(255,255,255,0.3);background:rgba(255,255,255,0.07);padding:2px 7px;border-radius:50px;">
                        {{ $project->todoTasks()->count() }}
                    </span>
                    @if(!$project->is_default)
                    <button onclick="event.stopPropagation();deleteProject({{ $project->id }}, '{{ addslashes($project->name) }}')"
                        style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.25);padding:2px;transition:color 0.2s;font-size:0.75rem;"
                        onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Sidebar Footer -->
        <div style="padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,0.08);">
            <button onclick="confirmLogout()" style="width:100%;display:flex;align-items:center;gap:10px;padding:0.6rem 0.75rem;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:8px;cursor:pointer;transition:all 0.2s;color:#fca5a5;font-family:'Plus Jakarta Sans',sans-serif;font-size:0.875rem;font-weight:600;"
                onmouseover="this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main style="flex:1;overflow-y:auto;display:flex;flex-direction:column;">

        <!-- Top Bar -->
        <div style="background:white;border-bottom:1px solid #e2e8f0;padding:1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;">
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="width:12px;height:12px;background:{{ $currentProject->color }};border-radius:50%;display:inline-block;"></span>
                <h1 style="font-size:1.1rem;font-weight:800;color:#0f172a;">{{ $currentProject->name }}</h1>
                <span id="total-badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.72rem;font-weight:700;padding:2px 9px;border-radius:50px;">
                    {{ $currentProject->todoTasks()->count() }} aktif
                </span>
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <!-- Sort -->
                <select id="sort-select" onchange="reloadTasks()" style="padding:0.4rem 0.75rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.8rem;font-family:'Plus Jakarta Sans',sans-serif;color:#475569;outline:none;cursor:pointer;background:white;">
                    <option value="asc">↑ Terlama</option>
                    <option value="desc">↓ Terbaru</option>
                </select>
                <!-- Layout toggle -->
                <div style="display:flex;gap:4px;background:#f1f5f9;padding:3px;border-radius:8px;">
                    <button id="btn-list" onclick="setLayout('list')" title="List" style="padding:5px 9px;border-radius:6px;border:none;cursor:pointer;background:white;color:#3b82f6;box-shadow:0 1px 3px rgba(0,0,0,0.1);transition:all 0.2s;">
                        <i class="fas fa-list" style="font-size:0.8rem;"></i>
                    </button>
                    <button id="btn-masonry" onclick="setLayout('masonry')" title="Grid" style="padding:5px 9px;border-radius:6px;border:none;cursor:pointer;background:transparent;color:#94a3b8;transition:all 0.2s;">
                        <i class="fas fa-th-large" style="font-size:0.8rem;"></i>
                    </button>
                </div>
                <!-- Add task button -->
                <button onclick="openAddTask()" style="display:flex;align-items:center;gap:6px;padding:0.5rem 1rem;background:linear-gradient(135deg,#3b5bdb,#4c6ef5);color:white;border:none;border-radius:8px;font-size:0.85rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 15px rgba(76,110,245,0.4)'"
                    onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <i class="fas fa-plus"></i> Tugas Baru
                </button>
            </div>
        </div>

        <!-- Body -->
        <div style="padding:1.5rem;flex:1;">

            <!-- QUICK ADD FORM (inline) -->
            <div id="quick-add-form" style="display:none;background:white;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.08);border:1.5px solid #dbeafe;animation:fadeIn 0.2s ease;">
                <h3 style="font-size:0.875rem;font-weight:700;color:#1e293b;margin-bottom:1rem;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-plus-circle" style="color:#3b82f6;"></i> Tambah Tugas Baru
                </h3>
                <div style="margin-bottom:0.75rem;">
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#64748b;margin-bottom:0.3rem;text-transform:uppercase;letter-spacing:0.06em;">Judul Tugas *</label>
                    <input type="text" id="quick-title" placeholder="Apa yang mau dikerjakan?" maxlength="255"
                        style="width:100%;padding:0.6rem 0.875rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.9rem;font-family:'Plus Jakarta Sans',sans-serif;outline:none;transition:all 0.2s;color:#1e293b;"
                        onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                        onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow=''">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.75rem;font-weight:700;color:#64748b;margin-bottom:0.3rem;text-transform:uppercase;letter-spacing:0.06em;">Deskripsi (opsional)</label>
                    <textarea id="quick-desc" placeholder="Detail tugas, catatan, atau checklist..." rows="3"
                        style="width:100%;padding:0.6rem 0.875rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.875rem;font-family:'Plus Jakarta Sans',sans-serif;outline:none;transition:all 0.2s;color:#1e293b;resize:vertical;min-height:70px;"
                        onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                        onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow=''"></textarea>
                </div>
                <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                    <button onclick="closeAddTask()" style="padding:0.5rem 1rem;background:white;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;color:#475569;transition:all 0.2s;"
                        onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        Batal
                    </button>
                    <button onclick="submitAddTask()" style="padding:0.5rem 1.25rem;background:linear-gradient(135deg,#3b5bdb,#4c6ef5);color:white;border:none;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all 0.2s;"
                        onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform=''">
                        <i class="fas fa-plus"></i> Tambah Tugas
                    </button>
                </div>
            </div>

            <!-- ACTIVE TASKS -->
            <div style="margin-bottom:1rem;display:flex;align-items:center;gap:10px;">
                <h2 style="font-size:0.875rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.06em;">
                    <i class="fas fa-tasks" style="color:#3b82f6;margin-right:6px;"></i>Tugas Aktif
                </h2>
                <span id="todo-count" style="background:#eff6ff;color:#1d4ed8;font-size:0.72rem;font-weight:700;padding:2px 8px;border-radius:50px;border:1px solid #bfdbfe;">
                    {{ $currentProject->todoTasks()->count() }}
                </span>
            </div>

            <div id="tasks-container" class="layout-list" style="margin-bottom:1.5rem;"></div>

            <div id="load-more-todo" style="text-align:center;display:none;margin-bottom:1.5rem;">
                <button onclick="loadMoreTodo()" style="padding:0.6rem 1.5rem;background:white;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.85rem;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;color:#475569;transition:all 0.2s;"
                    onmouseover="this.style.borderColor='#3b82f6';this.style.color='#3b82f6'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569'">
                    <i class="fas fa-chevron-down"></i> Muat 20 Tugas Lagi
                </button>
            </div>

            <!-- DIVIDER -->
            <div style="display:flex;align-items:center;gap:12px;margin:1.5rem 0;">
                <div style="flex:1;height:1px;background:linear-gradient(90deg,transparent,#e2e8f0 30%,#e2e8f0 70%,transparent);"></div>
                <span style="font-size:0.75rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;white-space:nowrap;">
                    <i class="fas fa-check-circle" style="color:#22c55e;margin-right:4px;"></i> Sudah Selesai
                </span>
                <div style="flex:1;height:1px;background:linear-gradient(90deg,transparent,#e2e8f0 30%,#e2e8f0 70%,transparent);"></div>
            </div>

            <!-- DONE TASKS -->
            <div style="margin-bottom:1rem;display:flex;align-items:center;gap:10px;">
                <h2 style="font-size:0.875rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.06em;">
                    <i class="fas fa-check-circle" style="color:#22c55e;margin-right:6px;"></i>Selesai
                </h2>
                <span id="done-count" style="background:#f0fdf4;color:#15803d;font-size:0.72rem;font-weight:700;padding:2px 8px;border-radius:50px;border:1px solid #bbf7d0;">
                    {{ $currentProject->doneTasks()->count() }}
                </span>
            </div>

            <div id="done-container" class="layout-list" style="margin-bottom:1.5rem;opacity:0.75;"></div>

            <div id="load-more-done" style="text-align:center;display:none;margin-bottom:2rem;">
                <button onclick="loadMoreDone()" style="padding:0.6rem 1.5rem;background:white;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.85rem;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;color:#475569;transition:all 0.2s;"
                    onmouseover="this.style.borderColor='#22c55e';this.style.color='#22c55e'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569'">
                    <i class="fas fa-chevron-down"></i> Muat 20 Tugas Selesai Lagi
                </button>
            </div>
        </div>
    </main>
</div>

<!-- TASK DETAIL MODAL -->
<div id="task-modal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)closeTaskModal()">
    <div class="modal-box">
        <div style="padding:1.5rem 1.5rem 0;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
                <div style="flex:1;">
                    <div id="modal-status-badge" style="display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;font-weight:700;padding:3px 10px;border-radius:50px;margin-bottom:0.75rem;"></div>
                    <h2 id="modal-title" style="font-size:1.2rem;font-weight:800;color:#0f172a;line-height:1.4;margin-bottom:0.5rem;"></h2>
                </div>
                <button onclick="closeTaskModal()" style="background:none;border:none;cursor:pointer;color:#94a3b8;padding:4px;font-size:1.1rem;transition:color 0.2s;flex-shrink:0;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#94a3b8'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div style="padding:0 1.5rem 1rem;">
            <div id="modal-dates" style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1rem;"></div>
            <div id="modal-desc-section" style="display:none;background:#f8fafc;border-radius:10px;padding:1rem;margin-bottom:1rem;">
                <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem;">
                    <i class="fas fa-align-left" style="margin-right:5px;"></i>Deskripsi
                </div>
                <div id="modal-desc" style="font-size:0.9rem;color:#334155;line-height:1.7;white-space:pre-wrap;"></div>
            </div>
        </div>

        <div style="padding:1rem 1.5rem 1.5rem;border-top:1px solid #f1f5f9;display:flex;gap:0.5rem;flex-wrap:wrap;">
            <button id="modal-complete-btn" style="flex:1;padding:0.6rem 1rem;border:none;border-radius:8px;font-size:0.85rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all 0.2s;"></button>
            <button onclick="openEditTask()" style="flex:1;padding:0.6rem 1rem;background:white;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.85rem;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;color:#475569;transition:all 0.2s;"
                onmouseover="this.style.borderColor='#3b82f6';this.style.color='#3b82f6'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569'">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button onclick="deleteCurrentTask()" style="padding:0.6rem 0.875rem;background:#fff5f5;border:1.5px solid #fecaca;border-radius:8px;font-size:0.85rem;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;color:#ef4444;transition:all 0.2s;"
                onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fff5f5'">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>

<!-- EDIT TASK MODAL -->
<div id="edit-modal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)closeEditModal()">
    <div class="modal-box">
        <div style="padding:1.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;"><i class="fas fa-edit" style="color:#3b82f6;margin-right:8px;"></i>Edit Tugas</h3>
                <button onclick="closeEditModal()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:1.1rem;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#94a3b8'"><i class="fas fa-times"></i></button>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.75rem;font-weight:700;color:#64748b;margin-bottom:0.35rem;text-transform:uppercase;letter-spacing:0.06em;">Judul *</label>
                <input type="text" id="edit-title" maxlength="255"
                    style="width:100%;padding:0.65rem 0.875rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.9rem;font-family:'Plus Jakarta Sans',sans-serif;outline:none;transition:all 0.2s;color:#1e293b;"
                    onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                    onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow=''">
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="display:block;font-size:0.75rem;font-weight:700;color:#64748b;margin-bottom:0.35rem;text-transform:uppercase;letter-spacing:0.06em;">Deskripsi</label>
                <textarea id="edit-desc" rows="4"
                    style="width:100%;padding:0.65rem 0.875rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.875rem;font-family:'Plus Jakarta Sans',sans-serif;outline:none;transition:all 0.2s;color:#1e293b;resize:vertical;min-height:80px;"
                    onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                    onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow=''"></textarea>
            </div>
            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                <button onclick="closeEditModal()" style="padding:0.6rem 1.25rem;background:white;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;color:#475569;">Batal</button>
                <button onclick="submitEditTask()" style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,#3b5bdb,#4c6ef5);color:white;border:none;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ADD PROJECT MODAL -->
<div id="add-project-modal" class="modal-overlay" style="display:none;" onclick="if(event.target===this)closeAddProject()">
    <div class="modal-box" style="max-width:400px;">
        <div style="padding:1.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;"><i class="fas fa-folder-plus" style="color:#3b82f6;margin-right:8px;"></i>Proyek Baru</h3>
                <button onclick="closeAddProject()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:1.1rem;"><i class="fas fa-times"></i></button>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.75rem;font-weight:700;color:#64748b;margin-bottom:0.35rem;text-transform:uppercase;letter-spacing:0.06em;">Nama Proyek *</label>
                <input type="text" id="project-name" maxlength="100" placeholder="Contoh: Pekerjaan, Pribadi..."
                    style="width:100%;padding:0.65rem 0.875rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.9rem;font-family:'Plus Jakarta Sans',sans-serif;outline:none;transition:all 0.2s;color:#1e293b;"
                    onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                    onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow=''">
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="display:block;font-size:0.75rem;font-weight:700;color:#64748b;margin-bottom:0.5rem;text-transform:uppercase;letter-spacing:0.06em;">Warna Label</label>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach(['#3B82F6','#6366F1','#8B5CF6','#EC4899','#EF4444','#F97316','#EAB308','#22C55E','#14B8A6','#06B6D4'] as $color)
                    <button type="button" onclick="selectColor('{{ $color }}')" data-color="{{ $color }}"
                        style="width:30px;height:30px;background:{{ $color }};border-radius:50%;border:3px solid transparent;cursor:pointer;transition:all 0.2s;"
                        class="color-btn"></button>
                    @endforeach
                </div>
                <input type="hidden" id="project-color" value="#3B82F6">
            </div>
            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                <button onclick="closeAddProject()" style="padding:0.6rem 1.25rem;background:white;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;color:#475569;">Batal</button>
                <button onclick="submitAddProject()" style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,#3b5bdb,#4c6ef5);color:white;border:none;border-radius:8px;font-size:0.875rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">
                    <i class="fas fa-plus"></i> Buat Proyek
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.layout-list .task-card { margin-bottom: 8px; }
.layout-masonry { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; }
.task-card { background: white; border-radius: 10px; border: 1.5px solid #f1f5f9; padding: 0.875rem 1rem; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 4px rgba(0,0,0,0.04); display: flex; align-items: flex-start; gap: 10px; animation: fadeIn 0.3s ease; }
.task-card:hover { border-color: #bfdbfe; box-shadow: 0 4px 12px rgba(59,130,246,0.1); transform: translateY(-1px); }
.task-done-card { opacity: 0.7; background: #fafafa; }
.task-done-card:hover { opacity: 0.9; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
</style>

@push('scripts')
<script>
const PROJECT_ID = {{ $currentProject->id }};
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// Standard headers for ALL fetch calls - Accept:application/json prevents Laravel from returning HTML on errors
const JSON_HEADERS = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': CSRF,
};
const ACTION_HEADERS = {
    'Accept': 'application/json',
    'X-CSRF-TOKEN': CSRF,
};

let currentTask = null;
let todoOffset = 0, doneOffset = 0;
let currentLayout = 'list';

// ── Helper: safe fetch that always returns parsed JSON ──────────────────────
async function apiFetch(url, options = {}) {
    try {
        const res = await fetch(url, options);
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch {
            console.error('Non-JSON response from', url, ':', text.substring(0, 200));
            return { success: false, message: 'Server error — cek console untuk detail.' };
        }
    } catch (err) {
        console.error('Fetch error:', err);
        return { success: false, message: 'Koneksi gagal.' };
    }
}

// ── Init ────────────────────────────────────────────────────────────────────
function initPage() {
    selectColor('#3B82F6');
    loadTasks('todo', 0, true);
    loadTasks('done', 0, true);
}

// ── Load tasks ──────────────────────────────────────────────────────────────
async function loadTasks(status, offset, reset = false) {
    const sort = document.getElementById('sort-select').value;
    const data = await apiFetch(
        `/tasks/load?project_id=${PROJECT_ID}&status=${status}&sort=${sort}&offset=${offset}`,
        { headers: ACTION_HEADERS }
    );
    if (!data.success) return;

    const container = document.getElementById(status === 'todo' ? 'tasks-container' : 'done-container');
    if (reset) container.innerHTML = '';

    data.tasks.forEach(task => container.appendChild(buildTaskCard(task)));

    if (status === 'todo') {
        todoOffset = offset + data.tasks.length;
        document.getElementById('load-more-todo').style.display = data.hasMore ? 'block' : 'none';
        document.getElementById('todo-count').textContent = data.total;
        document.getElementById('total-badge').textContent = data.total + ' aktif';
    } else {
        doneOffset = offset + data.tasks.length;
        document.getElementById('load-more-done').style.display = data.hasMore ? 'block' : 'none';
        document.getElementById('done-count').textContent = data.total;
    }

    if (reset) {
        document.getElementById('tasks-container').className = 'layout-' + currentLayout;
        document.getElementById('done-container').className = 'layout-' + currentLayout;
    }
}

// ── Build task card DOM ─────────────────────────────────────────────────────
function buildTaskCard(task) {
    const isDone = task.status === 'done';
    const div = document.createElement('div');
    div.className = 'task-card' + (isDone ? ' task-done-card' : '');
    div.dataset.id = task.id;
    div.onclick = () => openTaskModal(task);

    const checkDiv = document.createElement('div');
    checkDiv.className = 'checkbox-custom' + (isDone ? ' checked' : '');
    checkDiv.title = isDone ? 'Kembalikan ke aktif' : 'Tandai selesai';
    checkDiv.onclick = (e) => {
        e.stopPropagation();
        isDone ? returnTask(task.id, div) : completeTask(task.id, div);
    };
    if (isDone) checkDiv.innerHTML = '<i class="fas fa-check" style="font-size:9px;color:white;"></i>';

    const content = document.createElement('div');
    content.style.cssText = 'flex:1;min-width:0;';
    content.innerHTML = `
        <div style="font-size:0.875rem;font-weight:600;color:${isDone ? '#94a3b8' : '#0f172a'};line-height:1.4;${isDone ? 'text-decoration:line-through;' : ''}white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(task.title)}</div>
        ${task.description ? `<div style="font-size:0.775rem;color:#94a3b8;margin-top:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(task.description)}</div>` : ''}
        <div style="font-size:0.7rem;color:#cbd5e1;margin-top:5px;display:flex;align-items:center;gap:4px;">
            <i class="fas fa-calendar-alt" style="font-size:0.65rem;"></i>
            ${isDone ? (task.completed_at || task.created_at) : task.created_at}
        </div>`;

    div.appendChild(checkDiv);
    div.appendChild(content);
    return div;
}

function escHtml(str) {
    if (!str) return '';
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Complete / Return task ──────────────────────────────────────────────────
async function completeTask(id, cardEl) {
    // Optimistic: visually dim the card immediately
    if (cardEl) cardEl.style.opacity = '0.4';

    const data = await apiFetch(`/tasks/${id}/complete`, {
        method: 'POST',
        headers: ACTION_HEADERS,
    });

    if (data.success) {
        reloadTasks();
        toast('success', 'Tugas ditandai selesai! ✅');
    } else {
        if (cardEl) cardEl.style.opacity = '1';
        toast('error', data.message || 'Gagal menyelesaikan tugas.');
    }
}

async function returnTask(id, cardEl) {
    if (cardEl) cardEl.style.opacity = '0.4';

    const data = await apiFetch(`/tasks/${id}/return`, {
        method: 'POST',
        headers: ACTION_HEADERS,
    });

    if (data.success) {
        reloadTasks();
        toast('success', 'Tugas dikembalikan ke aktif!');
    } else {
        if (cardEl) cardEl.style.opacity = '1';
        toast('error', data.message || 'Gagal mengembalikan tugas.');
    }
}

// ── Task Modal ──────────────────────────────────────────────────────────────
function openTaskModal(task) {
    currentTask = task;
    const isDone = task.status === 'done';

    const badge = document.getElementById('modal-status-badge');
    badge.innerHTML = isDone ? '<i class="fas fa-check-circle"></i> Selesai' : '<i class="fas fa-clock"></i> Sedang Dikerjakan';
    badge.style.cssText = isDone
        ? 'display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;font-weight:700;padding:3px 10px;border-radius:50px;margin-bottom:0.75rem;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;'
        : 'display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;font-weight:700;padding:3px 10px;border-radius:50px;margin-bottom:0.75rem;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;';

    document.getElementById('modal-title').textContent = task.title;

    let datesHtml = `<div style="display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;color:#94a3b8;background:#f8fafc;border:1px solid #e2e8f0;padding:3px 9px;border-radius:50px;"><i class="fas fa-plus" style="font-size:0.65rem;"></i> ${task.created_at}</div>`;
    if (task.updated_at && task.updated_at !== task.created_at)
        datesHtml += `<div style="display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;color:#94a3b8;background:#f8fafc;border:1px solid #e2e8f0;padding:3px 9px;border-radius:50px;"><i class="fas fa-pen" style="font-size:0.65rem;"></i> ${task.updated_at}</div>`;
    if (task.completed_at)
        datesHtml += `<div style="display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;color:#15803d;background:#f0fdf4;border:1px solid #bbf7d0;padding:3px 9px;border-radius:50px;"><i class="fas fa-check" style="font-size:0.65rem;"></i> ${task.completed_at}</div>`;
    if (task.returned_at)
        datesHtml += `<div style="display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;color:#b45309;background:#fffbeb;border:1px solid #fde68a;padding:3px 9px;border-radius:50px;"><i class="fas fa-undo" style="font-size:0.65rem;"></i> ${task.returned_at}</div>`;
    document.getElementById('modal-dates').innerHTML = datesHtml;

    const descSection = document.getElementById('modal-desc-section');
    if (task.description) {
        descSection.style.display = 'block';
        document.getElementById('modal-desc').textContent = task.description;
    } else {
        descSection.style.display = 'none';
    }

    const btn = document.getElementById('modal-complete-btn');
    if (isDone) {
        btn.innerHTML = '<i class="fas fa-undo"></i> Kembalikan';
        btn.style.cssText = "flex:1;padding:0.6rem 1rem;border:1.5px solid #e2e8f0;background:white;color:#475569;border-radius:8px;font-size:0.85rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;";
        btn.onclick = () => { returnTask(task.id); closeTaskModal(); };
    } else {
        btn.innerHTML = '<i class="fas fa-check"></i> Tandai Selesai';
        btn.style.cssText = "flex:1;padding:0.6rem 1rem;border:none;background:linear-gradient(135deg,#16a34a,#22c55e);color:white;border-radius:8px;font-size:0.85rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;";
        btn.onclick = () => { completeTask(task.id); closeTaskModal(); };
    }

    document.getElementById('task-modal').style.display = 'flex';
}

function closeTaskModal() { document.getElementById('task-modal').style.display = 'none'; }

// ── Edit Task ───────────────────────────────────────────────────────────────
function openEditTask() {
    if (!currentTask) return;
    document.getElementById('edit-title').value = currentTask.title;
    document.getElementById('edit-desc').value = currentTask.description || '';
    document.getElementById('edit-modal').style.display = 'flex';
}
function closeEditModal() { document.getElementById('edit-modal').style.display = 'none'; }

async function submitEditTask() {
    if (!currentTask) return;
    const title = document.getElementById('edit-title').value.trim();
    if (!title) { Swal.fire({icon:'warning',title:'Oops!',text:'Judul tugas tidak boleh kosong.',confirmButtonColor:'#3b82f6'}); return; }

    const data = await apiFetch(`/tasks/${currentTask.id}`, {
        method: 'PUT',
        headers: JSON_HEADERS,
        body: JSON.stringify({ title, description: document.getElementById('edit-desc').value }),
    });

    if (data.success) {
        closeEditModal();
        closeTaskModal();
        reloadTasks();
        toast('success', 'Tugas berhasil diperbarui.');
    } else {
        toast('error', data.message || 'Gagal memperbarui tugas.');
    }
}

// ── Delete task ─────────────────────────────────────────────────────────────
function deleteCurrentTask() {
    if (!currentTask) return;
    Swal.fire({
        title: 'Hapus Tugas?',
        text: `"${currentTask.title}" akan dihapus permanen.`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
    }).then(async (result) => {
        if (!result.isConfirmed) return;
        const data = await apiFetch(`/tasks/${currentTask.id}`, {
            method: 'DELETE',
            headers: ACTION_HEADERS,
        });
        if (data.success) {
            closeTaskModal();
            reloadTasks();
            toast('success', 'Tugas berhasil dihapus.');
        } else {
            toast('error', data.message || 'Gagal menghapus tugas.');
        }
    });
}

// ── Add task ────────────────────────────────────────────────────────────────
function openAddTask() {
    document.getElementById('quick-add-form').style.display = 'block';
    document.getElementById('quick-title').focus();
}
function closeAddTask() {
    document.getElementById('quick-add-form').style.display = 'none';
    document.getElementById('quick-title').value = '';
    document.getElementById('quick-desc').value = '';
}

async function submitAddTask() {
    const title = document.getElementById('quick-title').value.trim();
    if (!title) { Swal.fire({icon:'warning',title:'Oops!',text:'Judul tugas tidak boleh kosong.',confirmButtonColor:'#3b82f6'}); return; }

    const data = await apiFetch('/tasks', {
        method: 'POST',
        headers: JSON_HEADERS,
        body: JSON.stringify({
            title,
            description: document.getElementById('quick-desc').value,
            project_id: PROJECT_ID,
        }),
    });

    if (data.success) {
        closeAddTask();
        // Optimistic: prepend the new card immediately
        const container = document.getElementById('tasks-container');
        const card = buildTaskCard(data.task);
        container.insertBefore(card, container.firstChild);
        // Then reload to get accurate counts
        reloadTasks();
        toast('success', 'Tugas baru berhasil ditambahkan!');
    } else {
        toast('error', data.message || 'Gagal menambahkan tugas.');
    }
}

// ── Reload helpers ──────────────────────────────────────────────────────────
function reloadTasks() {
    todoOffset = 0;
    doneOffset = 0;
    loadTasks('todo', 0, true);
    loadTasks('done', 0, true);
}
function loadMoreTodo() { loadTasks('todo', todoOffset, false); }
function loadMoreDone() { loadTasks('done', doneOffset, false); }

// ── Layout toggle ───────────────────────────────────────────────────────────
function setLayout(layout) {
    currentLayout = layout;
    document.getElementById('tasks-container').className = 'layout-' + layout;
    document.getElementById('done-container').className = 'layout-' + layout;
    const listActive  = 'padding:5px 9px;border-radius:6px;border:none;cursor:pointer;background:white;color:#3b82f6;box-shadow:0 1px 3px rgba(0,0,0,0.1);transition:all 0.2s;';
    const listInactive = 'padding:5px 9px;border-radius:6px;border:none;cursor:pointer;background:transparent;color:#94a3b8;transition:all 0.2s;';
    document.getElementById('btn-list').style.cssText    = layout === 'list'    ? listActive : listInactive;
    document.getElementById('btn-masonry').style.cssText = layout === 'masonry' ? listActive : listInactive;
}

// ── Project modal ───────────────────────────────────────────────────────────
function openAddProject()  { document.getElementById('add-project-modal').style.display = 'flex'; setTimeout(() => document.getElementById('project-name').focus(), 50); }
function closeAddProject() { document.getElementById('add-project-modal').style.display = 'none'; document.getElementById('project-name').value = ''; }

function selectColor(color) {
    document.getElementById('project-color').value = color;
    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.style.borderColor = btn.dataset.color === color ? '#0f172a' : 'transparent';
        btn.style.transform   = btn.dataset.color === color ? 'scale(1.2)' : 'scale(1)';
    });
}

async function submitAddProject() {
    const name = document.getElementById('project-name').value.trim();
    if (!name) { Swal.fire({icon:'warning',title:'Oops!',text:'Nama proyek tidak boleh kosong.',confirmButtonColor:'#3b82f6'}); return; }
    const color = document.getElementById('project-color').value;

    const data = await apiFetch('/projects', {
        method: 'POST',
        headers: JSON_HEADERS,
        body: JSON.stringify({ name, color }),
    });

    if (data.success) {
        closeAddProject();
        window.location = `/dashboard/project/${data.project.id}`;
    } else {
        toast('error', data.message || 'Gagal membuat proyek.');
    }
}

function deleteProject(id, name) {
    Swal.fire({
        title: 'Hapus Proyek?',
        html: `Proyek <strong>"${name}"</strong> dan semua tugasnya akan dihapus permanen.`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
    }).then(async (result) => {
        if (!result.isConfirmed) return;
        const data = await apiFetch(`/projects/${id}`, {
            method: 'DELETE',
            headers: ACTION_HEADERS,
        });
        if (data.success) window.location = '/dashboard';
        else Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message });
    });
}

// ── Logout ──────────────────────────────────────────────────────────────────
function confirmLogout() {
    Swal.fire({
        title: 'Keluar dari TugasKu?', text: 'Kamu yakin ingin logout sekarang?',
        icon: 'question', showCancelButton: true,
        confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Keluar', cancelButtonText: 'Batal',
    }).then((r) => { if (r.isConfirmed) document.getElementById('logout-form').submit(); });
}

// ── Toast helper ────────────────────────────────────────────────────────────
function toast(icon, text) {
    Swal.fire({ icon, text, timer: 2200, showConfirmButton: false, toast: true, position: 'top-end' });
}

// ── Keyboard shortcuts ──────────────────────────────────────────────────────
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { closeTaskModal(); closeEditModal(); closeAddProject(); closeAddTask(); }
    if (e.key === 'n' && !e.ctrlKey && !e.metaKey &&
        document.activeElement.tagName !== 'INPUT' &&
        document.activeElement.tagName !== 'TEXTAREA') {
        e.preventDefault(); openAddTask();
    }
});

// ── Enter to submit quick-add ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('quick-title')?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); submitAddTask(); }
    });
});

initPage();
</script>
@endpush
@endsection
