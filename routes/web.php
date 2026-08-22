<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\UserDetailController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

// =====================================================
// 1. EXECUTIVE DASHBOARD
// =====================================================
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// =====================================================
// 2. CLIENTS (360° HUB)
// =====================================================
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
Route::match(['put', 'post'], '/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
Route::match(['delete', 'post'], '/clients/{client}/delete', [ClientController::class, 'destroy'])->name('clients.destroy.post');
Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
Route::post('/clients/{client}/activity', [ClientController::class, 'addActivity'])->name('clients.activity');

// =====================================================
// 3. SALES DEALS & PIPELINE
// =====================================================
Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
Route::post('/deals', [DealController::class, 'store'])->name('deals.store');
Route::post('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.stage');
Route::delete('/deals/{deal}', [DealController::class, 'destroy'])->name('deals.destroy');

// =====================================================
// 4. INVOICES & BILLING (PDF PRINT)
// =====================================================
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
Route::post('/invoices/sync-site', [InvoiceController::class, 'syncFromUrl'])->name('invoices.sync');
Route::post('/invoices/upload-pdf', [InvoiceController::class, 'uploadPdf'])->name('invoices.upload-pdf');
Route::post('/api/v1/payment-webhook', [InvoiceController::class, 'webhook'])->name('api.payment.webhook');
Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'showPdf'])->name('invoices.pdf');
Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
Route::post('/invoices/{invoice}/toggle', [InvoiceController::class, 'toggleStatus'])->name('invoices.toggle');
Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

// =====================================================
// 5. PRODUCTS & INVENTORY
// =====================================================
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// =====================================================
// 6. SALES RECORDS
// =====================================================
Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');

// =====================================================
// 7. AI COPILOT (GEMINI ENGINE)
// =====================================================
Route::get('/ai-assistant', [AiAssistantController::class, 'index'])->name('ai.index');
Route::post('/ai-assistant/ask', [AiAssistantController::class, 'ask'])->name('ai.ask');
Route::post('/ai-assistant/clear', [AiAssistantController::class, 'clearHistory'])->name('ai.clear');

// =====================================================
// 8. AI LEADS INTELLIGENCE & WEBSITE SCRAPER
// =====================================================
Route::get('/user-details-list', [UserDetailController::class, 'index'])->name('user-details.list');
Route::get('/user-details', [UserDetailController::class, 'index'])->name('user-details');
Route::post('/user-details', [UserDetailController::class, 'store'])->name('user-details.store');
Route::post('/scrape-company', [UserDetailController::class, 'scrapeWebsite'])->name('scrape.company');
Route::post('/leads/{id}/convert', [UserDetailController::class, 'convertToClient'])->name('leads.convert');
Route::delete('/user-details/{id}', [UserDetailController::class, 'destroy'])->name('user-details.destroy');

// =====================================================
// 9. TASKS MANAGEMENT
// =====================================================
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

// =====================================================
// 10. REPORTS & BUSINESS ANALYTICS
// =====================================================
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// =====================================================
// 11. SETTINGS & PROFILE CONFIGURATIONS
// =====================================================
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
Route::post('/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile');