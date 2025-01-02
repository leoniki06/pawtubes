<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category; 
use App\Models\Konsultasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('author', 'category')->paginate(10); // Muat relasi author dan category
        $categories = Category::all(); // Ambil semua kategori
        return view('admin.news.index', compact('news', 'categories'));
    }

    public function domestic()
    {
        $domesticNews = News::where('category_id', 2)->get(); // Contoh untuk kategori tertentu
        return view('domestic', ['news' => $domesticNews]);
    }

    public function show($id)
    {
        $news = News::findOrFail($id); // Ambil berita berdasarkan ID
        $latestNews = News::latest()->take(5)->get(); // Ambil 5 berita terbaru
    
        return view('detail', compact('news', 'latestNews'));
    }
    
    public function news()
    {
        $news = News::with('author', 'category')->paginate(10); // Muat relasi author dan category
        $categories = Category::all(); // Ambil semua kategori
        return view('news', compact('news', 'categories'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q'); // Ambil kata kunci dari input form
        $news = News::where('title', 'LIKE', "%$keyword%")
            ->orWhere('content', 'LIKE', "%$keyword%")
            ->paginate(10);

        return view('search', compact('news', 'keyword'));
    }

    public function storeKonsultasi(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'nama_hewan' => 'required|string|max:100',
            'foto_hewan' => 'nullable|image|max:2048',
            'kategori_hewan' => 'required|in:Anjing,Kucing',
            'ras' => 'nullable|string|max:100',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'usia_hewan' => 'nullable|integer',
            'kontak' => 'nullable|string|max:15',
        ]);
    
        // Simpan data ke database
        $konsultasi = new Konsultasi();
        $konsultasi->nama_pemilik = $request->nama_pemilik;
        $konsultasi->nama_hewan = $request->nama_hewan;
        if ($request->hasFile('foto_hewan')) {
            $path = $request->file('foto_hewan')->store('public/foto_hewan');
            $konsultasi->foto_hewan = $path;
        }
        $konsultasi->kategori_hewan = $request->kategori_hewan;
        $konsultasi->ras = $request->ras;
        $konsultasi->jenis_kelamin = $request->jenis_kelamin;
        $konsultasi->usia_hewan = $request->usia_hewan;
        $konsultasi->kontak = $request->kontak;
        $konsultasi->save();
    
        return redirect()->back()->with('success', 'Konsultasi berhasil disimpan.');
    }
    
    
    
}
