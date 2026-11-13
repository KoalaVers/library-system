<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BorrowingController extends Controller
{
    /**
     * Menampilkan daftar transaksi peminjaman.
     */
    public function index()
    {
        $borrowings = Borrowing::with(['member', 'user'])
            ->latest()
            ->paginate(10);

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Menampilkan form peminjaman.
     */
    public function create()
    {
        $members = Member::orderBy('name')->get();

        $books = Book::where('stock', '>', 0)
            ->orderBy('title')
            ->get();

        return view('borrowings.create', compact(
            'members',
            'books'
        ));
    }

    /**
     * Menyimpan transaksi peminjaman.
     */
    public function store(Request $request)
    {
        
            $booksInput = collect($request->input('books', []))
                ->filter(function ($item) {
                    return !empty($item['book_id']);
                })
                ->values()
                ->all();

            $request->merge([
                'books' => $booksInput,
            ]);

            $validated = $request->validate([
            'member_id' => [
                'required',
                'exists:members,id',
            ],

            'books' => [
                'required',
                'array',
                'min:1',
            ],

            'books.*.book_id' => [
                'required',
                'integer',
                'distinct',
                'exists:books,id',
            ],

            'books.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $borrowing = DB::transaction(function () use ($validated) {

            $borrowing = Borrowing::create([
                'user_id' => auth()->id(),
                'member_id' => $validated['member_id'],
                'borrow_date' => now()->toDateString(),
                'due_date' => now()->addDays(7)->toDateString(),
                'return_date' => null,
                'status' => 'borrowed',
            ]);

            foreach ($validated['books'] as $item) {

                $book = Book::whereKey($item['book_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$book) {
                    throw ValidationException::withMessages([
                        'books' => 'Buku tidak ditemukan.',
                    ]);
                }

                if ($book->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'books' => "Stok buku {$book->title} tidak mencukupi. Stok tersedia: {$book->stock}.",
                    ]);
                }

                $borrowing->details()->create([
                    'book_id' => $book->id,
                    'quantity' => $item['quantity'],
                ]);

                $book->decrement('stock', $item['quantity']);
            }

            return $borrowing;
        });

        return redirect()
            ->route('borrowings.show', $borrowing->id)
            ->with('success', 'Peminjaman buku berhasil disimpan.');
    }

    /**
     * Menampilkan detail transaksi.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load([
            'member',
            'user',
            'details.book',
        ]);

        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Memproses pengembalian buku.
     */
    public function returnBook(Borrowing $borrowing)
    {
        DB::transaction(function () use ($borrowing) {

            $borrowing = Borrowing::whereKey($borrowing->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($borrowing->status === 'returned') {
                throw ValidationException::withMessages([
                    'return' => 'Buku pada transaksi ini sudah dikembalikan.',
                ]);
            }

            $details = $borrowing->details()->get();

            foreach ($details as $detail) {

                $book = Book::whereKey($detail->book_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $book->increment('stock', $detail->quantity);
            }

            $borrowing->update([
                'return_date' => now()->toDateString(),
                'status' => 'returned',
            ]);
        });

        return redirect()
            ->route('borrowings.show', $borrowing->id)
            ->with('success', 'Pengembalian buku berhasil diproses.');
    }
}