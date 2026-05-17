<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'  => 'required|string|max:100',
            'color' => 'required|string|size:7',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $maxOrder = Project::where('user_id', Auth::id())->max('order') ?? 0;

        $project = Project::create([
            'user_id' => Auth::id(),
            'name'    => $request->name,
            'color'   => $request->color,
            'order'   => $maxOrder + 1,
        ]);

        return response()->json(['success' => true, 'project' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name'  => 'required|string|max:100',
            'color' => 'required|string|size:7',
        ]);

        $project->update(['name' => $request->name, 'color' => $request->color]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'project' => $project]);
        }

        return redirect()->back()->with('success', 'Proyek berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        if ($project->user_id !== Auth::id()) abort(403);
        if ($project->is_default) {
            return response()->json(['success' => false, 'message' => 'Proyek default tidak dapat dihapus.'], 422);
        }
        $project->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('dashboard')->with('success', 'Proyek berhasil dihapus!');
    }
}
