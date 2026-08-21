<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Invoice;
use App\Models\Sale;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')->get();
        $deals = Deal::with('client')->get();
        $sales = Sale::with('client')->get();
        $clients = Client::all();
        $leads = UserDetail::all();

        $totalRevenue = $invoices->where('status', 'Paid')->sum('amount');
        $pendingRevenue = $invoices->where('status', 'Pending')->sum('amount');
        $pipelineValue = $deals->whereNotIn('stage', ['won', 'lost'])->sum('amount');
        $wonDealsValue = $deals->where('stage', 'won')->sum('amount');

        $dealsWonCount = $deals->where('stage', 'won')->count();
        $totalDealsCount = $deals->count();
        $conversionRate = $totalDealsCount > 0 ? round(($dealsWonCount / $totalDealsCount) * 100) : 0;

        return view('reports.index', compact(
            'totalRevenue',
            'pendingRevenue',
            'pipelineValue',
            'wonDealsValue',
            'conversionRate',
            'invoices',
            'deals',
            'clients',
            'leads'
        ));
    }
}
