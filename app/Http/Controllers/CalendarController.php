<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $events = CalendarEvent::with('client')->orderBy('start_time', 'asc')->get();
        $clients = Client::all();

        return view('calendar.index', compact('events', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:meeting,call,deadline,reminder',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        CalendarEvent::create($validated);

        return redirect()->route('calendar.index')->with('success', 'Calendar Event scheduled!');
    }

    public function destroy(CalendarEvent $event)
    {
        $event->delete();
        return redirect()->route('calendar.index')->with('success', 'Event removed.');
    }
}
