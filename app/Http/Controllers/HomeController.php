<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Branch;
use App\Models\Product;
use App\Models\PublicationReport;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $settings = Setting::getAllKeyValues();

        $allProducts = Product::active()->get();
        $productsByCat = [
            'tabungan' => $allProducts->where('category', 'tabungan')->values(),
            'deposito' => $allProducts->where('category', 'deposito')->values(),
            'kredit' => $allProducts->where('category', 'kredit')->values(),
        ];

        $branches = Branch::active()->get();
        $articles = Article::published()->take(6)->get();
        $reports = PublicationReport::active()->get();

        return view('landing', compact('settings', 'allProducts', 'productsByCat', 'branches', 'articles', 'reports'));
    }
}
