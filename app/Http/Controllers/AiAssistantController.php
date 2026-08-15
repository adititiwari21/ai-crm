<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sale;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function index()
    {
        return view('ai.assistant');
    }

    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
        ]);

        $question = strtolower(trim($request->question));

        // =====================================================
        // CRM DATA
        // =====================================================

        $totalClients = Client::count();

        $totalSales = Sale::sum('amount');

        $totalRevenue = Invoice::sum('amount');

        $pendingInvoices = Invoice::where(
            'status',
            'Pending'
        )->count();

        $totalProducts = Product::count();

        $totalUsers = UserDetail::count();

        $analyzedCompanies = UserDetail::whereNotNull(
            'website_title'
        )->count();


        // =====================================================
        // COMPANY ANALYSIS QUESTIONS
        // IMPORTANT: KEEP THIS BEFORE GENERAL QUESTIONS
        // =====================================================

        if (
            str_contains($question, 'companies') ||
            str_contains($question, 'company') ||
            str_contains($question, 'analyzed companies') ||
            str_contains($question, 'analysed companies') ||
            str_contains($question, 'company analysis') ||
            str_contains($question, 'website analysis')
        ) {

            $answer =
                "You currently have {$analyzedCompanies} analyzed companies in your CRM.";
        }


        // =====================================================
        // LEAD / USER QUESTIONS
        // =====================================================

        elseif (
            str_contains($question, 'lead') ||
            str_contains($question, 'leads') ||
            str_contains($question, 'user details') ||
            str_contains($question, 'user detail')
        ) {

            $answer =
                "You currently have {$totalUsers} user detail records in your CRM.";
        }


        // =====================================================
        // CLIENT QUESTIONS
        // =====================================================

        elseif (
            str_contains($question, 'client') ||
            str_contains($question, 'clients') ||
            str_contains($question, 'customer') ||
            str_contains($question, 'customers')
        ) {

            $clients = Client::latest()
                ->take(5)
                ->get();

            if ($clients->count() > 0) {

                $names = $clients
                    ->pluck('name')
                    ->implode(', ');

                $answer =
                    "You currently have {$totalClients} clients. " .
                    "Some recent clients are: {$names}.";

            } else {

                $answer =
                    "There are currently no clients in your CRM.";
            }
        }


        // =====================================================
        // SALES QUESTIONS
        // =====================================================

        elseif (
            str_contains($question, 'sale') ||
            str_contains($question, 'sales')
        ) {

            $answer =
                "Your total recorded sales are ₹" .
                number_format($totalSales, 2) .
                ".";
        }


        // =====================================================
        // REVENUE QUESTIONS
        // =====================================================

        elseif (
            str_contains($question, 'revenue') ||
            str_contains($question, 'income')
        ) {

            $answer =
                "Your total invoice revenue is ₹" .
                number_format($totalRevenue, 2) .
                ".";
        }


        // =====================================================
        // INVOICE QUESTIONS
        // =====================================================

        elseif (
            str_contains($question, 'invoice') ||
            str_contains($question, 'invoices') ||
            str_contains($question, 'pending')
        ) {

            $answer =
                "You currently have {$pendingInvoices} pending invoices.";
        }


        // =====================================================
        // PRODUCT QUESTIONS
        // =====================================================

        elseif (
            str_contains($question, 'product') ||
            str_contains($question, 'products') ||
            str_contains($question, 'inventory') ||
            str_contains($question, 'stock')
        ) {

            $answer =
                "You currently have {$totalProducts} products in your inventory.";
        }


        // =====================================================
        // GENERAL CRM QUESTIONS
        // =====================================================

        elseif (
            str_contains($question, 'overview') ||
            str_contains($question, 'summary') ||
            str_contains($question, 'dashboard')
        ) {

            $answer =
                "Here is your CRM summary: " .
                "{$totalClients} clients, " .
                "₹" . number_format($totalSales, 2) . " total sales, " .
                "₹" . number_format($totalRevenue, 2) . " invoice revenue, " .
                "{$pendingInvoices} pending invoices, " .
                "{$totalProducts} products, " .
                "{$totalUsers} user records, and " .
                "{$analyzedCompanies} analyzed companies.";
        }


        // =====================================================
        // GREETING
        // =====================================================

        elseif (
            str_contains($question, 'hello') ||
            str_contains($question, 'hi') ||
            str_contains($question, 'hey')
        ) {

            $answer =
                "Hello! 👋 I am your AI CRM Assistant. " .
                "Ask me about clients, sales, revenue, invoices, products, leads, companies or your CRM summary.";
        }


        // =====================================================
        // UNKNOWN QUESTION
        // =====================================================

        else {

            $answer =
                "I can help you with your CRM data. Try asking: " .
                "'How many clients do we have?', " .
                "'What are our total sales?', " .
                "'How many pending invoices?', " .
                "'How many products do we have?', " .
                "'How many leads do we have?', " .
                "'How many companies were analyzed?', " .
                "or 'Give me a CRM summary.'";
        }


        return back()->with('answer', $answer);
    }
}