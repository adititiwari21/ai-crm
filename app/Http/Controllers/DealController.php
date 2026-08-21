<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with('client')->latest()->get();
        $clients = Client::all();

        $stages = [
            'lead' => [
                'name' => 'New Lead',
                'badge' => 'badge-new',
                'dot' => '#3b82f6',
                'deals' => $deals->where('stage', 'lead'),
            ],
            'qualified' => [
                'name' => 'Qualified',
                'badge' => 'badge-qualified',
                'dot' => '#10b981',
                'deals' => $deals->where('stage', 'qualified'),
            ],
            'proposal' => [
                'name' => 'Proposal',
                'badge' => 'badge-proposal',
                'dot' => '#8b5cf6',
                'deals' => $deals->where('stage', 'proposal'),
            ],
            'negotiation' => [
                'name' => 'Negotiation',
                'badge' => 'badge-negotiation',
                'dot' => '#f59e0b',
                'deals' => $deals->where('stage', 'negotiation'),
            ],
            'won' => [
                'name' => 'Closed Won',
                'badge' => 'badge-closed-won',
                'dot' => '#06b6d4',
                'deals' => $deals->where('stage', 'won'),
            ],
        ];

        return view('deals.index', compact('deals', 'clients', 'stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'stage' => 'required|in:lead,qualified,proposal,negotiation,won,lost',
            'probability' => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        Deal::create($validated);

        return redirect()->route('deals.index')->with('success', 'Deal created in Pipeline!');
    }

    public function updateStage(Request $request, $id)
    {
        $request->validate([
            'stage' => 'required|in:lead,qualified,proposal,negotiation,won,lost',
        ]);

        $deal = Deal::findOrFail($id);
        $deal->update(['stage' => $request->stage]);

        return response()->json([
            'success' => true,
            'message' => "Deal updated to stage {$request->stage}",
        ]);
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('deals.index')->with('success', 'Deal removed.');
    }
}
