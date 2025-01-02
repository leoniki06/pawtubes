<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $news = News::with('category')->paginate(10);
        $categories = Category::all(); // Mengambil semua kategori dari database
        return view('dashboard', compact('news', 'categories'));
    }
}
