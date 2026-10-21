<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        // Ambil data buku beserta kategorinya.
        $books = Book::with('category')->get();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        // Ambil kategori untuk pilihan dropdown.
        $categories = Category::orderBy('name')->get();

        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $currentYear = date('Y');

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:100|unique:books,title',
            'author' => 'required|string|min:3|max:255',
            'year' => "required|integer|min:1900|max:$currentYear",
            'stock' => 'required|integer|min:0|max:1000',
            'category_id' => 'required|exists:categories,id',
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
        ]);

        Book::create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $book->load('category');

        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();

        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $currentYear = date('Y');

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:100|unique:books,title,' . $book->id,
            'author' => 'required|string|min:3|max:255',
            'year' => "required|integer|min:1900|max:$currentYear",
            'stock' => 'required|integer|min:0|max:1000',
            'category_id' => 'required|exists:categories,id',
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
        ]);

        $book->update($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil dihapus.');
    }
}
