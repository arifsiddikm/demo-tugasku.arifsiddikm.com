<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request, $projectId = null)
    {
        $user = Auth::user();
        $projects = $user->projects()->orderBy('order')->get();

        if ($projects->isEmpty()) {
            $project = Project::create([
                'user_id' => $user->id, 'name' => 'Main Project',
                'color' => '#3B82F6', 'is_default' => true, 'order' => 0,
            ]);
            $projects = collect([$project]);
        }

        $currentProject = $projectId
            ? $projects->where('id', $projectId)->first() ?? $projects->first()
            : $projects->first();

        return view('dashboard.index', compact('projects', 'currentProject'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id'  => 'required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $project = Project::where('id', $request->project_id)
            ->where('user_id', Auth::id())->first();

        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Proyek tidak ditemukan.'], 404);
        }

        $maxOrder = Task::where('project_id', $project->id)->max('order') ?? 0;

        $task = Task::create([
            'user_id'     => Auth::id(),
            'project_id'  => $project->id,
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => 'todo',
            'order'       => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'task'    => $this->formatTask($task),
        ]);
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'task' => $this->formatTask($task->fresh())]);
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $task->delete();
        return response()->json(['success' => true]);
    }

    public function complete(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $task->update(['status' => 'done', 'completed_at' => now(), 'returned_at' => null]);
        return response()->json(['success' => true, 'task' => $this->formatTask($task->fresh())]);
    }

    public function returnTask(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $task->update(['status' => 'todo', 'returned_at' => now(), 'completed_at' => null]);
        return response()->json(['success' => true, 'task' => $this->formatTask($task->fresh())]);
    }

    public function loadTasks(Request $request)
    {
        $request->validate(['project_id' => 'required|exists:projects,id']);
        $project = Project::where('id', $request->project_id)
            ->where('user_id', Auth::id())->firstOrFail();

        $status  = $request->get('status', 'todo');
        $sort    = $request->get('sort', 'asc');
        $offset  = (int) $request->get('offset', 0);
        $limit   = $status === 'todo' ? 20 : 10;

        $query = Task::where('project_id', $project->id)
            ->where('status', $status)
            ->orderBy('order', $sort);

        $total = $query->count();
        $tasks = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'success'  => true,
            'tasks'    => $tasks->map(fn($t) => $this->formatTask($t)),
            'hasMore'  => ($offset + $limit) < $total,
            'total'    => $total,
        ]);
    }

    private function formatTask(Task $task): array
    {
        return [
            'id'           => $task->id,
            'title'        => $task->title,
            'description'  => $task->description,
            'status'       => $task->status,
            'order'        => $task->order,
            'project_id'   => $task->project_id,
            'created_at'   => $task->created_at?->format('d M Y, H:i'),
            'completed_at' => $task->completed_at?->format('d M Y, H:i'),
            'returned_at'  => $task->returned_at?->format('d M Y, H:i'),
            'updated_at'   => $task->updated_at?->format('d M Y, H:i'),
        ];
    }
}
