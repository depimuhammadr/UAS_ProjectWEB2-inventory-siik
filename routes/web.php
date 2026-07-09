<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MasterController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products Routes (Accessible to both admin and user, but write ops restricted in Controller/Policy)
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/export/csv', [ProductController::class, 'exportCSV'])->name('export.csv');
        Route::get('/export/pdf', [ProductController::class, 'exportPDF'])->name('export.pdf');
        
        // Admin Only actions on products
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
            Route::post('/import/csv', [ProductController::class, 'importCSV'])->name('import.csv');
        });
    });

    // Borrowings Routes
    Route::prefix('borrowings')->name('borrowings.')->group(function () {
        Route::get('/', [BorrowingController::class, 'index'])->name('index');
        Route::get('/export/csv', [BorrowingController::class, 'exportCSV'])->name('export.csv');
        Route::get('/export/pdf', [BorrowingController::class, 'exportPDF'])->name('export.pdf');

        // User Only: request borrowing
        Route::middleware('role:user')->group(function () {
            Route::post('/', [BorrowingController::class, 'store'])->name('store');
        });

        // Admin Only: approve, reject, return borrowings
        Route::middleware('role:admin')->group(function () {
            Route::post('/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('approve');
            Route::post('/{borrowing}/reject', [BorrowingController::class, 'reject'])->name('reject');
            Route::post('/{borrowing}/return', [BorrowingController::class, 'return'])->name('return');
        });
    });

    // Categories Routes (Admin / Super Admin)
    Route::prefix('categories')->name('categories.')->middleware('role:admin')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Branches & Divisions Administration (Super Admin Only)
    Route::prefix('master')->name('master.')->middleware('role:admin')->group(function () {
        Route::get('/', [MasterController::class, 'index'])->name('index');
        Route::post('/branches', [MasterController::class, 'storeBranch'])->name('branches.store');
        Route::post('/divisions', [MasterController::class, 'storeDivision'])->name('divisions.store');
    });
});
