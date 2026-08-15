<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sale;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\UserDetail;

class DashboardController extends Controller
{
    public function index()
    {
        // =====================================================
        // BASIC CRM METRICS
        // =====================================================

        $totalClients = Client::count();

        $totalSales = Sale::sum('amount');

        $totalRevenue = Invoice::sum('amount');

        $pendingInvoices = Invoice::where(
            'status',
            'Pending'
        )->count();

        $totalProducts = Product::count();


        // =====================================================
        // USER / LEAD METRICS
        // =====================================================

        $totalUsers = UserDetail::count();

        $analyzedCompanies = UserDetail::whereNotNull(
            'website_title'
        )->count();


        // =====================================================
        // RECENT SALES
        // =====================================================

        $recentSales = Sale::with('client')
            ->latest()
            ->take(5)
            ->get();


        // =====================================================
        // RECENT USER DETAILS
        // =====================================================

        $recentUsers = UserDetail::latest()
            ->take(5)
            ->get();


        // =====================================================
        // RECENT COMPANY ANALYSIS
        // =====================================================

        $recentCompanyAnalyses = UserDetail::whereNotNull(
            'website_title'
        )
        ->latest()
        ->take(5)
        ->get();


        // =====================================================
        // DASHBOARD
        // =====================================================

        return view('dashboard', compact(
            'totalClients',
            'totalSales',
            'totalRevenue',
            'pendingInvoices',
            'totalProducts',
            'totalUsers',
            'analyzedCompanies',
            'recentSales',
            'recentUsers',
            'recentCompanyAnalyses'
        ));
    }
}