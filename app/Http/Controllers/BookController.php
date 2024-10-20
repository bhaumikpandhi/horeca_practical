<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\StoreRequest;
use App\Http\Requests\Book\UpdateRequest;
use App\Http\Resources\BookResource;
use App\Models\Book\Book;
use App\Repository\BookRepository;
use Illuminate\Http\Request;

class BookController extends Controller
{

    protected BookRepository $repository;

    /**
     * @param BookRepository $repository
     */
    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $books = $this->repository->index($request);

        return BookResource::collection($books)
            ->additional(['message' => 'Books fetched successfully']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $book = $this->repository->store($request->all());

        return response()->json([
            'data' => $book,
            'message' => 'Book created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book = $this->repository->show($book);

        return response()->json([
            'data' => $book,
            'message' => 'Book fetched successfully'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Book $book)
    {
        $book = $this->repository->update($book, $request->all());

        return response()->json([
            'data' => $book,
            'message' => 'Book updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $this->repository->destroy($book);

        return response()->json([
            'message' => 'Book deleted successfully'
        ]);
    }
}
