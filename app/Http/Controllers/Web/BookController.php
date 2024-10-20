<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book\Genre;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $genres = Genre::query()->get();

        return view('book.index', ['genres' => $genres]);
    }
}
