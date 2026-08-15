<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\UserDetailController;


// =====================================================
// DASHBOARD
// =====================================================

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');


// =====================================================
// CLIENTS
// =====================================================

Route::get('/clients', [ClientController::class, 'index'])
    ->name('clients.index');

Route::post('/clients', [ClientController::class, 'store'])
    ->name('clients.store');

Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])
    ->name('clients.edit');

Route::put('/clients/{client}', [ClientController::class, 'update'])
    ->name('clients.update');

Route::delete('/clients/{client}', [ClientController::class, 'destroy'])
    ->name('clients.destroy');


// =====================================================
// SALES
// =====================================================

Route::get('/sales', [SaleController::class, 'index'])
    ->name('sales.index');

Route::post('/sales', [SaleController::class, 'store'])
    ->name('sales.store');

Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])
    ->name('sales.edit');

Route::put('/sales/{sale}', [SaleController::class, 'update'])
    ->name('sales.update');

Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])
    ->name('sales.destroy');


// =====================================================
// INVOICES
// =====================================================

Route::get('/invoices', [InvoiceController::class, 'index'])
    ->name('invoices.index');

Route::post('/invoices', [InvoiceController::class, 'store'])
    ->name('invoices.store');

Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])
    ->name('invoices.edit');

Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])
    ->name('invoices.update');

Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])
    ->name('invoices.destroy');


// =====================================================
// PRODUCTS
// =====================================================

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::post('/products', [ProductController::class, 'store'])
    ->name('products.store');

Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
    ->name('products.edit');

Route::put('/products/{product}', [ProductController::class, 'update'])
    ->name('products.update');

Route::delete('/products/{product}', [ProductController::class, 'destroy'])
    ->name('products.destroy');


// =====================================================
// AI ASSISTANT
// =====================================================

Route::get('/ai-assistant', [AiAssistantController::class, 'index'])
    ->name('ai.index');

Route::post('/ai-assistant/ask', [AiAssistantController::class, 'ask'])
    ->name('ai.ask');


// =====================================================
// USER DETAILS
// =====================================================

// Show user details form
Route::get('/user-details', function () {
    return view('user-details');
})
    ->name('user-details');


// Save user details
Route::post('/user-details', [UserDetailController::class, 'store'])
    ->name('user-details.store');


// Scrape company website
Route::post('/scrape-company', [UserDetailController::class, 'scrapeWebsite'])
    ->name('scrape.company');


// User details list
Route::get('/user-details-list', [UserDetailController::class, 'index'])
    ->name('user-details.list');


// Delete user details
Route::delete('/user-details/{id}', [UserDetailController::class, 'destroy'])
    ->name('user-details.destroy');