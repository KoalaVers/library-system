<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    private function buildFilteredQuery(Request $request)
    {
        $query = Book::with('category');

        // 1. Pencarian berdasarkan judul atau penulis
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // 2. Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 3. Filter berdasarkan ketersediaan stok
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', '=', 0);
            }
        }

        return $query->latest();
    }

    public function index(Request $request)
    {
        $books = $this->buildFilteredQuery($request)
            ->paginate(5)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function exportCsv(Request $request)
    {
        $books = $this->buildFilteredQuery($request)->get();
        $fileName = 'daftar-buku-' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($books) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 agar Excel membaca kolom dengan rapi
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom CSV
            fputcsv($file, ['ID Buku', 'Judul Buku', 'Penulis', 'Tahun Terbit', 'Kategori', 'Jumlah Stok']);

            foreach ($books as $book) {
                fputcsv($file, [
                    $book->id,
                    $book->title,
                    $book->author,
                    $book->year,
                    $book->category?->name ?? 'Belum ada kategori',
                    $book->stock,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255|unique:books,title',
            'author'      => 'required|string|min:3|max:255',
            'year'        => 'required|integer|min:1900|max:' . date('Y'),
            'stock'       => 'required|integer|min:0|max:1000',
            'category_id' => 'required|exists:categories,id',
            'cover'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'title.required'       => 'Judul buku wajib diisi.',
            'title.unique'         => 'Judul buku sudah terdaftar dalam database.',
            'author.required'      => 'Nama penulis wajib diisi.',
            'author.min'           => 'Nama penulis minimal harus 3 karakter.',
            'year.required'        => 'Tahun terbit wajib diisi.',
            'year.integer'         => 'Tahun terbit harus berupa angka bulat.',
            'year.min'             => 'Tahun terbit minimal tahun 1900.',
            'year.max'             => 'Tahun terbit tidak boleh melebihi tahun saat ini.',
            'stock.required'       => 'Stok buku wajib diisi.',
            'stock.integer'        => 'Stok buku harus berupa angka.',
            'stock.min'            => 'Stok buku tidak boleh kurang dari 0 (negatif).',
            'stock.max'            => 'Stok buku maksimal adalah 1000.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'category_id.exists'   => 'Kategori yang dipilih tidak valid atau tidak tersedia.',
            'cover.image'          => 'File cover harus berupa gambar.',
            'cover.mimes'          => 'Format cover harus jpeg, png, jpg, atau webp.',
            'cover.max'            => 'Ukuran file cover maksimal 2MB.',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255|unique:books,title,' . $book->id,
            'author'      => 'required|string|min:3|max:255',
            'year'        => 'required|integer|min:1900|max:' . date('Y'),
            'stock'       => 'required|integer|min:0|max:1000',
            'category_id' => 'required|exists:categories,id',
            'cover'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'title.required'       => 'Judul buku wajib diisi.',
            'title.unique'         => 'Judul buku sudah terdaftar dalam database.',
            'author.required'      => 'Nama penulis wajib diisi.',
            'author.min'           => 'Nama penulis minimal harus 3 karakter.',
            'year.required'        => 'Tahun terbit wajib diisi.',
            'year.integer'         => 'Tahun terbit harus berupa angka bulat.',
            'year.min'             => 'Tahun terbit minimal tahun 1900.',
            'year.max'             => 'Tahun terbit tidak boleh melebihi tahun saat ini.',
            'stock.required'       => 'Stok buku wajib diisi.',
            'stock.integer'        => 'Stok buku harus berupa angka.',
            'stock.min'            => 'Stok buku tidak boleh kurang dari 0 (negatif).',
            'stock.max'            => 'Stok buku maksimal adalah 1000.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'category_id.exists'   => 'Kategori yang dipilih tidak valid atau tidak tersedia.',
            'cover.image'          => 'File cover harus berupa gambar.',
            'cover.mimes'          => 'Format cover harus jpeg, png, jpg, atau webp.',
            'cover.max'            => 'Ukuran file cover maksimal 2MB.',
        ]);

        if ($request->hasFile('cover')) {
            // Hapus file cover lama jika ada agar tidak menumpuk
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if (class_exists('App\\Models\\BorrowingDetail') && $book->borrowingDetails()->exists()) {
            return back()->with('error', 'Buku tidak dapat dihapus karena sudah digunakan dalam transaksi peminjaman.');
        }

        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}