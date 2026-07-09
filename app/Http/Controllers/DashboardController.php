<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Borrowing;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // 1. Base query boundaries depending on Branch Separation
        if ($user->isSuperAdmin()) {
            $totalProducts = Product::sum('stock');
            $activeBorrowings = Borrowing::where('status', 'approved')->count();
            $pendingBorrowings = Borrowing::where('status', 'pending')->count();
            
            $recentBorrowings = Borrowing::with(['user', 'product', 'user.branch', 'user.division'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            $totalBranches = Branch::count();
        } elseif ($user->isAdmin()) {
            // Branch-isolated Admin statistics
            $branchId = $user->branch_id;
            $totalProducts = Product::where('branch_id', $branchId)->sum('stock');
            
            $activeBorrowings = Borrowing::where('status', 'approved')
                ->whereHas('product', function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })->count();
                
            $pendingBorrowings = Borrowing::where('status', 'pending')
                ->whereHas('product', function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })->count();
                
            $recentBorrowings = Borrowing::with(['user', 'product', 'user.division'])
                ->whereHas('product', function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            $totalBranches = null; // branch admins don't need this stat
        } else {
            // Regular user: can only see their own statistics
            $branchId = $user->branch_id;
            $totalProducts = Product::where('branch_id', $branchId)->count(); // Available products to borrow at their branch
            
            $activeBorrowings = Borrowing::where('user_id', $user->id)
                ->where('status', 'approved')
                ->count();
                
            $pendingBorrowings = Borrowing::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();
                
            $recentBorrowings = Borrowing::with(['product'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            $totalBranches = null;
        }

        // Generate dynamic statistics for a Chart.js visual dashboard
        // We will output monthly borrowings count for the current branch/all branches
        $monthlyChartData = $this->getMonthlyChartData($user);

        return view('dashboard', compact(
            'totalProducts', 
            'activeBorrowings', 
            'pendingBorrowings', 
            'recentBorrowings',
            'totalBranches',
            'monthlyChartData'
        ));
    }

    private function getMonthlyChartData($user)
    {
        $months = [];
        $counts = [];

        // For sqlite or mysql, let's query the last 6 months borrowing statistics
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $query = Borrowing::whereYear('borrow_date', $date->year)
                ->whereMonth('borrow_date', $date->month);

            if ($user->isSuperAdmin()) {
                // No filters
            } elseif ($user->isAdmin()) {
                $branchId = $user->branch_id;
                $query->whereHas('product', function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            } else {
                $query->where('user_id', $user->id);
            }

            $counts[] = $query->count();
        }

        return [
            'labels' => $months,
            'dataset' => $counts
        ];
    }
}
