<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingsController;

Route::get('/issue_invoice', [InvoiceController::class, 'showInvoiceForm'])->name('issue_invoice');

Route::post('/api/create_invoice', [InvoiceController::class, 'createInvoice'])->name('api_create_invoice');

Route::get('/order', [InvoiceController::class, 'viewInvoice'])->name('public_invoice');



Route::get('/list_invoices', [InvoiceController::class, 'listInvoices'])->name('list_invoices');


Route::get('/download_invoice', [InvoiceController::class, 'downloadInvoice'])->name('download_invoice');


Route::get('/update_invoice', [InvoiceController::class, 'showUpdateInvoiceForm'])->name('update_invoice');


Route::get('/api/invoice/{id}', [InvoiceController::class, 'getInvoice'])
    ->where('id', '.*'); // allows traversal like ../

Route::get('/api/v1/invoice', [InvoiceController::class, 'getInvoiceSQL'])->name('vulnerable_invoice');


Route::post('/api/v2/update_invoice', [InvoiceController::class, 'updateInvoiceSafe']);
Route::post('/api/v1/update_invoice', [InvoiceController::class, 'updateInvoiceVuln']);

Route::get('/update_settings', [SettingsController::class, 'renderSettingsPage'])->name('update_settings_page');

