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

        $totalRevenue = Invoice::where('status', 'Paid')->sum('amount');
        if ($totalRevenue <= 0) {
            $totalRevenue = Sale::sum('amount');
        }

        $pendingInvoices = Invoice::where('status', 'Pending')->count();

        $totalProducts = Product::count();

        // Monthly Revenue & Sales Forecasting
        $currentMonthSales = Sale::where('status', 'Paid')
            ->whereMonth('sale_date', date('m'))
            ->whereYear('sale_date', date('Y'))
            ->sum('amount');

        $monthlyRevenue = $currentMonthSales > 0 ? $currentMonthSales : $totalSales;
        $dayOfMonth = max(1, (int)date('j'));
        $monthlySalesForecast = $monthlyRevenue > 0 ? ($monthlyRevenue / $dayOfMonth) * 30 : 0;

        // =====================================================
        // USER / LEAD METRICS
        // =====================================================

        $totalUsers = UserDetail::count();

        $analyzedCompanies = UserDetail::whereNotNull('website_title')->count();

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

        $recentCompanyAnalyses = UserDetail::whereNotNull('website_title')
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
            'monthlyRevenue',
            'monthlySalesForecast',
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