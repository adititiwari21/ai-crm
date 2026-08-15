<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('client')
            ->latest()
            ->get();

        $clients = Client::all();

        return view('sales.index', compact('sales', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'status' => 'required|in:Paid,Pending',
        ]);

        Sale::create([
            'client_id' => $request->client_id,
            'amount' => $request->amount,
            'sale_date' => $request->sale_date,
            'status' => $request->status,
        ]);

        return redirect()->route('sales.index');
    }

    public function edit(Sale $sale)
    {
        $clients = Client::all();

        return view('sales.edit', compact('sale', 'clients'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'status' => 'required|in:Paid,Pending',
        ]);

        $sale->update([
            'client_id' => $request->client_id,
            'amount' => $request->amount,
            'sale_date' => $request->sale_date,
            'status' => $request->status,
        ]);

        return redirect()->route('sales.index');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index');
    }
}