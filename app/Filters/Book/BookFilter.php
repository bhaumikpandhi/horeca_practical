<?php

namespace App\Filters\Book;

use App\Filters\BaseFilter;
use App\Filters\Book\Apply\AuthorFilter;

class BookFilter extends BaseFilter
{
    protected array $filters = [
        'author' => AuthorFilter::class
    ];
}
