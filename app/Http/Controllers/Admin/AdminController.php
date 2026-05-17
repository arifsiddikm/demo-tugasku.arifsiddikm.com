<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers    = User::where('role', 'user')->count();
        $totalAdmins   = User::where('role', 'admin')->count();
        $totalProjects = Project::count();
        $totalTasks    = Task::count();
        $doneTasks     = Task::where('status', 'done')->count();
        $todoTasks     = Task::where('status', 'todo')->count();
        $recentUsers   = User::where('role', 'user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalAdmins', 'totalProjects',
            'totalTasks', 'doneTasks', 'todoTasks', 'recentUsers'
        ));
    }

    public function users(Request $request)
    {
        $search = $request->get('search');
        $users = User::where('role', 'user')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%"))
            ->withCount(['tasks', 'projects'])
            ->latest()->paginate(15);

        return view('admin.users', compact('users', 'search'));
    }

    public function toggleUser(User $user)
    {
        if ($user->isAdmin()) abort(403);
        $user->update(['is_active' => !$user->is_active]);
        return response()->json(['success' => true, 'is_active' => $user->fresh()->is_active]);
    }

    public function deleteUser(User $user)
    {
        if ($user->isAdmin()) abort(403);
        $user->delete();
        return response()->json(['success' => true]);
    }

    public function admins(Request $request)
    {
        $search = $request->get('search');
        $admins = User::where('role', 'admin')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%"))
            ->latest()->paginate(15);

        return view('admin.admins', compact('admins', 'search'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return redirect()->route('admin.admins')->with('success', 'Akun admin berhasil ditambahkan!');
    }

    public function updateAdmin(Request $request, User $admin)
    {
        if (!$admin->isAdmin()) abort(403);
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = ['name' => $request->name, 'email' => $request->email];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);
        return redirect()->route('admin.admins')->with('success', 'Akun admin berhasil diperbarui!');
    }

    public function deleteAdmin(User $admin)
    {
        if (!$admin->isAdmin()) abort(403);
        if (User::where('role', 'admin')->count() <= 1) {
            return response()->json(['success' => false, 'message' => 'Minimal harus ada 1 admin.'], 422);
        }
        $admin->delete();
        return response()->json(['success' => true]);
    }
}
