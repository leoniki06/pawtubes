<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('author', 'category')->paginate(10);
        $categories = Category::all();
    
        return view('dashboard', compact('news', 'categories'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Simpan file ke folder public/image
        $photo = $request->file('photo');
        $photoPath = 'image/' . time() . '_' . $photo->getClientOriginalName();
        $photo->move(public_path('image'), $photoPath);
    
        // Simpan data ke database
        News::create([
            'title' => $request->title,
            'author' => $request->author,
            'content' => $request->content,
            'image' => $photoPath,
            'category_id' => $request->category_id,
        ]);
    
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'content' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $news = News::findOrFail($id);

        // Perbarui data berita
        $news->title = $request->input('title');
        $news->author = $request->input('author');
        $news->category_id = $request->input('category_id');
        $news->content = $request->input('content');

        // Periksa apakah ada file baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($news->image && file_exists(public_path($news->image))) {
                unlink(public_path($news->image));
            }

            // Simpan foto baru ke folder public/image
            $photo = $request->file('photo');
            $photoPath = 'image/' . time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('image'), $photoPath);
            $news->image = $photoPath;
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // Hapus file gambar jika ada
        if ($news->image && file_exists(public_path($news->image))) {
            unlink(public_path($news->image));
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}
