<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Task $task)
    {
        return $task->comments()->with('user:id,name')->latest()->get();
    }

    public function store(Request $request, Task $task)
    {
        $data = $request->validate([
            'content' => 'required|string'
        ]);

        $comment = $task->comments()->create([
            'content' => $data['content'],
            'user_id' => Auth::id()
        ]);

        return $comment->load('user:id,name');
    }
}
