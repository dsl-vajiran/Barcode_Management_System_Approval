<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\ItemMasterController;
use App\Http\Controllers\GRNController;
use App\Http\Controllers\ItemIssueController;
use App\Http\Controllers\BatteryReturnController;
use App\Http\Controllers\GRNReprintController;
use App\Http\Controllers\GRNReturnController;
use App\Http\Controllers\ManualIssueController;
use App\Http\Controllers\BarcodeApprovalController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login or dashboard
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Barcode Routes
    Route::prefix('barcode')->name('barcode.')->group(function () {
        Route::get('/', [BarcodeController::class, 'index'])->name('index');
        Route::post('/search', [BarcodeController::class, 'search'])->name('search');
        Route::post('/return', [BarcodeController::class, 'return'])->name('return');
        Route::get('/{barcode}', [BarcodeController::class, 'show'])->name('show');
    });
    
    // Item Master Routes
    Route::prefix('item-master')->name('item.')->middleware('role:admin,warehouse_manager')->group(function () {
        Route::get('/', [ItemMasterController::class, 'index'])->name('index');
        Route::get('/create', [ItemMasterController::class, 'create'])->name('create');
        Route::post('/', [ItemMasterController::class, 'store'])->name('store');
        Route::get('/{itmcode}/edit', [ItemMasterController::class, 'edit'])->name('edit');
        Route::put('/{itmcode}', [ItemMasterController::class, 'update'])->name('update');
        Route::post('/search', [ItemMasterController::class, 'search'])->name('search');
        Route::get('/{itmcode}/barcode', [ItemMasterController::class, 'getItemForBarcode'])->name('barcode');
    });

    // GRN Routes
    Route::prefix('grn')->name('grn.')->middleware('role:admin,warehouse_manager')->group(function () {
        Route::get('/', [GRNController::class, 'index'])->name('index');
        Route::get('/create', [GRNController::class, 'create'])->name('create');
        Route::post('/', [GRNController::class, 'store'])->name('store');
        Route::get('/item-codes', [GRNController::class, 'itemCodes'])->name('item-codes');
        Route::get('/warehouse-codes', [GRNController::class, 'warehouseCodes'])->name('warehouse-codes');
        Route::get('/print-stickers', [GRNController::class, 'printStickers'])->name('print-stickers');
        Route::post('/print-selected', [GRNController::class, 'printSelected'])->name('print-selected');
        Route::get('/{gbarcode}', [GRNController::class, 'show'])->name('show');
        Route::get('/{gbarcode}/print', [GRNController::class, 'print'])->name('print');
        Route::post('/search', [GRNController::class, 'search'])->name('search');
    });

    // Item Issue Routes
    Route::prefix('item-issue')->name('item-issue.')->group(function () {
        Route::get('/', [ItemIssueController::class, 'index'])->name('index');
        Route::get('/create', [ItemIssueController::class, 'create'])->name('create');
        Route::post('/', [ItemIssueController::class, 'store'])->name('store');
        Route::post('/issue-barcode', [ItemIssueController::class, 'issueByBarcode'])->name('issue-barcode');
        Route::get('/{ibarcode}', [ItemIssueController::class, 'show'])->name('show');
    });

    // Battery Return Routes
    Route::prefix('battery-return')->name('battery-return.')->group(function () {
        Route::get('/', [BatteryReturnController::class, 'index'])->name('index');
        Route::post('/return', [BatteryReturnController::class, 'return'])->name('return');
        Route::post('/search', [BatteryReturnController::class, 'search'])->name('search');
    });

    // GRN Reprint Routes
    Route::prefix('grn-reprint')->name('grn-reprint.')->group(function () {
        Route::get('/', [GRNReprintController::class, 'index'])->name('index');
        Route::post('/search', [GRNReprintController::class, 'search'])->name('search');
        Route::get('/{gbarcode}/reprint', [GRNReprintController::class, 'reprint'])->name('reprint');
    });

    // GRN Return Routes
    Route::prefix('grn-return')->name('grn-return.')->group(function () {
        Route::get('/', [GRNReturnController::class, 'index'])->name('index');
        Route::post('/search', [GRNReturnController::class, 'search'])->name('search');
        Route::post('/return', [GRNReturnController::class, 'return'])->name('return');
    });

    // Manual Issue Routes
    Route::prefix('manual-issue')->name('manual-issue.')->group(function () {
        Route::get('/', [ManualIssueController::class, 'index'])->name('index');
        Route::get('/create', [ManualIssueController::class, 'create'])->name('create');
        Route::post('/', [ManualIssueController::class, 'store'])->name('store');
        Route::get('/{ibarcode}', [ManualIssueController::class, 'show'])->name('show');
        Route::get('/item/{itmcode}', [ManualIssueController::class, 'getItemDetails'])->name('item-details');
    });

    // Barcode Approval Routes
    Route::prefix('barcode-approval')->name('barcode-approval.')->group(function () {
        Route::get('/', [BarcodeApprovalController::class, 'index'])->name('index');
        Route::post('/search-barcode', [BarcodeApprovalController::class, 'searchBarcode'])->name('search-barcode');
        Route::post('/store', [BarcodeApprovalController::class, 'store'])->name('store');
        Route::post('/{ibarcode}/approve', [BarcodeApprovalController::class, 'approve'])->name('approve');
        Route::post('/approve-multiple', [BarcodeApprovalController::class, 'approveMultiple'])->name('approve-multiple');
        Route::get('/{ibarcode}', [BarcodeApprovalController::class, 'show'])->name('show');
    });

    // Approved Barcodes Routes
    Route::get('/approved-barcodes', [\App\Http\Controllers\ApprovedBarcodeController::class, 'index'])->name('approved-barcodes.index');

    // User Management Routes (Admin Only)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('toggle-active');
    });
});

