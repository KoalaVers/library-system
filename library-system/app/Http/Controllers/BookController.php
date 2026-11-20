<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Book::class);

        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', '=', 0);
            }
        }

        $books = $query->orderBy('title')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        Gate::authorize('create', Book::class);

        $categories = Category::orderBy('name')->get();

        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Book::class);

        $currentYear = date('Y');

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:100|unique:books,title',
            'author' => 'required|string|min:3|max:255',
            'year' => "required|integer|min:1900|max:$currentYear",
            'stock' => 'required|integer|min:0|max:1000',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.min' => 'Judul buku minimal 3 karakter.',
            'title.max' => 'Judul buku maksimal 100 karakter.',
            'title.unique' => 'Judul buku sudah terdaftar.',
            'author.required' => 'Nama penulis wajib diisi.',
            'author.min' => 'Nama penulis minimal 3 karakter.',
            'author.max' => 'Nama penulis maksimal 255 karakter.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'year.integer' => 'Tahun terbit harus berupa angka bulat.',
            'year.min' => 'Tahun terbit minimal 1900.',
            'year.max' => "Tahun terbit tidak boleh melebihi $currentYear.",
            'stock.required' => 'Stok buku wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
            'stock.max' => 'Stok maksimal adalah 1000.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'cover.image' => 'File cover harus berupa gambar.',
            'cover.mimes' => 'Format cover harus JPG, JPEG, PNG, atau WEBP.',
            'cover.max' => 'Ukuran file cover maksimal 2MB.',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        Gate::authorize('view', $book);

        $book->load('category');

        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        Gate::authorize('update', $book);

        $categories = Category::orderBy('name')->get();

        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        Gate::authorize('update', $book);

        $currentYear = date('Y');

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:100|unique:books,title,' . $book->id,
            'author' => 'required|string|min:3|max:255',
            'year' => "required|integer|min:1900|max:$currentYear",
            'stock' => 'required|integer|min:0|max:1000',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'title.min' => 'Judul buku minimal 3 karakter.',
            'title.max' => 'Judul buku maksimal 100 karakter.',
            'title.unique' => 'Judul buku sudah terdaftar.',
            'author.required' => 'Nama penulis wajib diisi.',
            'author.min' => 'Nama penulis minimal 3 karakter.',
            'author.max' => 'Nama penulis maksimal 255 karakter.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'year.integer' => 'Tahun terbit harus berupa angka bulat.',
            'year.min' => 'Tahun terbit minimal 1900.',
            'year.max' => "Tahun terbit tidak boleh melebihi $currentYear.",
            'stock.required' => 'Stok buku wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
            'stock.max' => 'Stok maksimal adalah 1000.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'cover.image' => 'File cover harus berupa gambar.',
            'cover.mimes' => 'Format cover harus JPG, JPEG, PNG, atau WEBP.',
            'cover.max' => 'Ukuran file cover maksimal 2MB.',
        ]);

        $oldCover = $book->cover;

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($validated);

        if ($request->hasFile('cover') && $oldCover && Storage::disk('public')->exists($oldCover)) {
            Storage::disk('public')->delete($oldCover);
        }

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        Gate::authorize('delete', $book);

        // Lindungi buku yang memiliki riwayat peminjaman.
        if ($book->borrowingDetails()->exists()) {
            return redirect()
                ->route('books.index')
                ->with(
                    'error',
                    'Buku tidak dapat dihapus karena sudah tercatat dalam riwayat peminjaman.'
                );
        }

        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }

    public function exportCsv(Request $request)
    {
        Gate::authorize('viewAny', Book::class);

        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', '=', 0);
            }
        }

        $books = $query->orderBy('title')->get();
        $filename = 'books_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($books) {
            $file = fopen('php://output', 'w');
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
}