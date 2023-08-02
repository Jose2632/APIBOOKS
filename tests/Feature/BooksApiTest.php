<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_can_get_books_test(): void
    {

        $books = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
    }

    public function test_can_get_book_test(): void
    {

        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    public function test_can_create_books_test(): void
    {
        $this->postJson(route('books.store'), [])->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'My new books'
        ])->assertJsonFragment([
            'title' => 'My new books'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'My new books'
        ]);
    }

    public function test_can_update_books_test(): void
    {

        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'update book'
        ])->assertJsonFragment([
            'title' => 'update book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'update book'
        ]);
    }

    public function test_can_delete_books_test(): void
    {

        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}
