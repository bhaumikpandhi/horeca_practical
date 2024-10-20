<?php

namespace App\Repository;

use App\Models\Book\Book;

class BookRepository
{
    /**
     * @param $request
     * @return mixed
     */
    public function index($request)
    {
        return Book::query()
            ->with('genre')
            ->filter($request)
            ->latest()
            ->paginate(10);
    }

    /**
     * @param Book $book
     * @return Book
     */
    public function show(Book $book)
    {
        $book->load('genre');

        return $book;
    }

    /**
     * @param $attributes
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store($attributes)
    {
        return Book::query()
            ->create([
                'title' => data_get($attributes, 'title'),
                'author' => data_get($attributes, 'author'),
                'published_date' => data_get($attributes, 'published_date'),
                'genre_id' => data_get($attributes, 'genre_id'),
            ]);
    }

    /**
     * @param Book $book
     */
    public function destroy(Book $book)
    {
        $book->delete();
    }

    /**
     * @param Book $book
     * @param $attributes
     * @return Book
     */
    public function update(Book $book, $attributes)
    {
        $book->update([
            'title' => data_get($attributes, 'title'),
            'author' => data_get($attributes, 'author'),
            'published_date' => data_get($attributes, 'published_date'),
            'genre_id' => data_get($attributes, 'genre_id'),
        ]);

        return $book;
    }

}
