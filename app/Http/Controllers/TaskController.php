<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index() {
        $user = Task::all();
        return $user;
    }

    public function show(Task $task) {
        return $task->load('user:id,name');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:backlog,in_progress,completed',
            'user_id' => 'required|exists:users,id'
        ]);

        $task = Task::create($data);
        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task) {

        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:backlog,in_progress,completed'
        ]);

        $task->update($data);
        return response()->json($task);
    }

    public function destroy(Task $task) {
        $task->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function stats() {
        $user = Auth::user();
        $total = $user->tasks()->count();
        $backlog = $user->tasks()->where('status', 'backlog')->count();
        $in_progress = $user->tasks()->where('status', 'in_progress')->count();
        $completed = $user->tasks()->where('status', 'completed')->count();

        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

        return response()->json([
            'total' => $total,
            'distribution' => [
                'backlog' => $backlog,
                'in_progress' => $in_progress,
                'completed' => $completed
            ],
            'percentage' => $percentage
        ]);
    }
}
