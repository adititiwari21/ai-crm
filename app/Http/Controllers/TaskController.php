<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('client')->latest()->get();
        $clients = Client::all();

        $todoCount = $tasks->where('status', 'To Do')->count();
        $inProgressCount = $tasks->where('status', 'In Progress')->count();
        $completedCount = $tasks->where('status', 'Completed')->count();

        return view('tasks.index', compact('tasks', 'clients', 'todoCount', 'inProgressCount', 'completedCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:High,Medium,Low',
            'status' => 'required|in:To Do,In Progress,Completed',
            'due_date' => 'nullable|date',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:To Do,In Progress,Completed',
        ]);

        $task->update(['status' => $request->status]);

        return back()->with('success', 'Task status updated!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }
}
