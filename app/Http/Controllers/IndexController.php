<?php

namespace App\Http\Controllers;

use App\Models\News;

class IndexController extends Controller
{
    public function index()
    {
        // Ambil berita utama (berita terbaru)
        $mainNews = News::latest()->first();

        // Ambil 4 berita lainnya untuk grid
        $news = News::latest()->skip(1)->take(4)->get();

        // Ambil berita terbaru untuk sidebar
        $latestNews = News::latest()->take(5)->get();

        return view('index', [
            'mainNews' => $mainNews,
            'news' => $news,
            'latestNews' => $latestNews,
        ]);
    }
}

