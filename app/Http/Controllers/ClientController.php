<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with(['deals', 'invoices'])->latest()->get();
        return view('clients.index', compact('clients'));
    }

    public function show(Client $client)
    {
        $client->load(['deals', 'invoices', 'activities']);
        return view('clients.show', compact('client'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
        ]);

        $client = Client::create($validated);

        // Add initial activity
        ClientActivity::create([
            'client_id' => $client->id,
            'type' => 'note',
            'description' => 'Client account created in CRM Pro.',
            'performed_at' => Carbon::now(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client added successfully!');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client->id)->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client removed.');
    }

    public function addActivity(Request $request, Client $client)
    {
        $request->validate([
            'type' => 'required|in:note,call,meeting,email,task',
            'description' => 'required|string',
        ]);

        ClientActivity::create([
            'client_id' => $client->id,
            'type' => $request->type,
            'description' => $request->description,
            'performed_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Activity logged to client timeline!');
    }
}