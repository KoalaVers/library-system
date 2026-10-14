<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $currentYear = date('Y');

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:100|unique:books,title',
            'author' => 'required|string|min:3|max:255',
            'year' => "required|integer|min:1900|max:$currentYear",
            'stock' => 'required|integer|min:0|max:1000',
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
        ]);

        Book::create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $currentYear = date('Y');

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:100|unique:books,title,' . $book->id,
            'author' => 'required|string|min:3|max:255',
            'year' => "required|integer|min:1900|max:$currentYear",
            'stock' => 'required|integer|min:0|max:1000',
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
