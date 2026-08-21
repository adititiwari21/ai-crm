<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::orderBy('created_at', 'asc')->get();
        return view('messages.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'recipient_name' => 'nullable|string',
        ]);

        $validated['sender_name'] = 'Administrator';
        $validated['recipient_name'] = $validated['recipient_name'] ?? 'Team Channel';

        Message::create($validated);

        return redirect()->route('messages.index');
    }
}
