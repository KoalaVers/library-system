<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Petugas Test',
            'email' => 'petugas@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->member = Member::create([
            'member_code' => 'M001',
            'name' => 'Anggota Test',
            'email' => 'anggota@test.com',
            'phone' => '08123456789',
            'address' => 'Karawang',
        ]);

        $this->actingAs($this->user);
    }

    private function createBook(string $title, int $stock): Book
    {
        return Book::create([
            'title' => $title,
            'author' => 'Penulis Test',
            'year' => '2026',
            'stock' => $stock,
        ]);
    }

    public function test_can_borrow_one_book_and_reduce_stock(): void
    {
        $book = $this->createBook('Buku Pertama', 10);

        $response = $this->post(route('borrowings.store'), [
            'member_id' => $this->member->id,
            'books' => [
                [
                    'book_id' => $book->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $borrowing = Borrowing::first();

        $response->assertRedirect(
            route('borrowings.show', $borrowing->id)
        );

        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'user_id' => $this->user->id,
            'member_id' => $this->member->id,
            'status' => 'borrowed',
        ]);

        $this->assertDatabaseHas('borrowing_details', [
            'borrowing_id' => $borrowing->id,
            'book_id' => $book->id,
            'quantity' => 2,
        ]);

        $this->assertEquals(8, $book->fresh()->stock);
    }

    public function test_can_borrow_multiple_books_in_one_transaction(): void
    {
        $book1 = $this->createBook('Buku A', 10);
        $book2 = $this->createBook('Buku B', 8);

        $response = $this->post(route('borrowings.store'), [
            'member_id' => $this->member->id,
            'books' => [
                [
                    'book_id' => $book1->id,
                    'quantity' => 2,
                ],
                [
                    'book_id' => $book2->id,
                    'quantity' => 3,
                ],
            ],
        ]);

        $response->assertRedirect();

        $this->assertEquals(8, $book1->fresh()->stock);
        $this->assertEquals(5, $book2->fresh()->stock);

        $this->assertDatabaseCount('borrowing_details', 2);
    }

    public function test_cannot_borrow_more_than_available_stock(): void
    {
        $book = $this->createBook('Buku Stok Terbatas', 2);

        $response = $this->from(route('borrowings.create'))
            ->post(route('borrowings.store'), [
                'member_id' => $this->member->id,
                'books' => [
                    [
                        'book_id' => $book->id,
                        'quantity' => 5,
                    ],
                ],
            ]);

        $response->assertSessionHasErrors('books');

        $this->assertDatabaseCount('borrowings', 0);
        $this->assertDatabaseCount('borrowing_details', 0);

        $this->assertEquals(2, $book->fresh()->stock);
    }

    public function test_cannot_borrow_book_with_zero_stock(): void
    {
        $book = $this->createBook('Buku Habis', 0);

        $response = $this->from(route('borrowings.create'))
            ->post(route('borrowings.store'), [
                'member_id' => $this->member->id,
                'books' => [
                    [
                        'book_id' => $book->id,
                        'quantity' => 1,
                    ],
                ],
            ]);

        $response->assertSessionHasErrors('books');

        $this->assertDatabaseCount('borrowings', 0);
        $this->assertEquals(0, $book->fresh()->stock);
    }

    public function test_borrowing_due_date_is_seven_days_after_borrow_date(): void
    {
        $book = $this->createBook('Buku Tanggal', 10);

        $this->post(route('borrowings.store'), [
            'member_id' => $this->member->id,
            'books' => [
                [
                    'book_id' => $book->id,
                    'quantity' => 1,
                ],
            ],
        ]);

        $borrowing = Borrowing::firstOrFail();

        $this->assertEquals(
            $borrowing->borrow_date->copy()->addDays(7)->toDateString(),
            $borrowing->due_date->toDateString()
        );
    }

    public function test_can_return_borrowed_books_and_restore_stock(): void
    {
        $book = $this->createBook('Buku Pengembalian', 10);

        $this->post(route('borrowings.store'), [
            'member_id' => $this->member->id,
            'books' => [
                [
                    'book_id' => $book->id,
                    'quantity' => 3,
                ],
            ],
        ]);

        $borrowing = Borrowing::firstOrFail();

        $this->assertEquals(7, $book->fresh()->stock);

        $response = $this->post(
            route('borrowings.return', $borrowing->id)
        );

        $response->assertRedirect(
            route('borrowings.show', $borrowing->id)
        );

        $this->assertEquals(10, $book->fresh()->stock);

        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'status' => 'returned',
        ]);

        $this->assertNotNull(
            $borrowing->fresh()->return_date
        );
    }

    public function test_cannot_return_already_returned_transaction_twice(): void
    {
        $book = $this->createBook('Buku Sekali Kembali', 10);

        $this->post(route('borrowings.store'), [
            'member_id' => $this->member->id,
            'books' => [
                [
                    'book_id' => $book->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $borrowing = Borrowing::firstOrFail();

        $this->post(
            route('borrowings.return', $borrowing->id)
        );

        $response = $this->from(
            route('borrowings.show', $borrowing->id)
        )->post(
            route('borrowings.return', $borrowing->id)
        );

        $response->assertSessionHasErrors('return');

        // Stok tidak boleh bertambah untuk kedua kalinya.
        $this->assertEquals(10, $book->fresh()->stock);
    }

    public function test_cannot_borrow_zero_or_negative_quantity(): void
    {
        $book = $this->createBook('Buku Validasi', 10);

        foreach ([0, -1] as $quantity) {
            $response = $this->from(route('borrowings.create'))
                ->post(route('borrowings.store'), [
                    'member_id' => $this->member->id,
                    'books' => [
                        [
                            'book_id' => $book->id,
                            'quantity' => $quantity,
                        ],
                    ],
                ]);

            $response->assertSessionHasErrors('books.0.quantity');
        }

        $this->assertDatabaseCount('borrowings', 0);
        $this->assertEquals(10, $book->fresh()->stock);
    }

    public function test_cannot_add_same_book_twice_in_one_transaction(): void
    {
        $book = $this->createBook('Buku Duplikat', 10);

        $response = $this->from(route('borrowings.create'))
            ->post(route('borrowings.store'), [
                'member_id' => $this->member->id,
                'books' => [
                    [
                        'book_id' => $book->id,
                        'quantity' => 1,
                    ],
                    [
                        'book_id' => $book->id,
                        'quantity' => 2,
                    ],
                ],
            ]);

        $response->assertSessionHasErrors();

        $this->assertDatabaseCount('borrowings', 0);
        $this->assertEquals(10, $book->fresh()->stock);
    }
}