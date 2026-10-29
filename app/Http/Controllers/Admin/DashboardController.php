<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Branch;
use App\Models\LoanApplication;
use App\Models\Product;
use App\Models\PublicationReport;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $metrics = [
            'total_products' => Product::count(),
            'total_tabungan' => Product::where('category', 'tabungan')->count(),
            'total_deposito' => Product::where('category', 'deposito')->count(),
            'total_kredit' => Product::where('category', 'kredit')->count(),
            'total_branches' => Branch::count(),
            'total_articles' => Article::count(),
            'total_reports' => PublicationReport::count(),
            'total_loans' => LoanApplication::count(),
            'loans_pending' => LoanApplication::where('status', 'pending')->count(),
            'loans_contacted' => LoanApplication::where('status', 'contacted')->count(),
            'loans_approved' => LoanApplication::where('status', 'approved')->count(),
            'loans_rejected' => LoanApplication::where('status', 'rejected')->count(),
            'total_nominal_loans' => LoanApplication::sum('nominal'),
        ];

        $recentLoans = LoanApplication::latest()->take(6)->get();
        $recentArticles = Article::latest()->take(3)->get();

        return view('admin.dashboard.index', compact('metrics', 'recentLoans', 'recentArticles'));
    }
}
