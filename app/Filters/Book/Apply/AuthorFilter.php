<?php

namespace App\Filters\Book\Apply;

use Illuminate\Database\Eloquent\Builder;

class AuthorFilter
{
    /**
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function filter(Builder $builder, $value)
    {
        return $builder->where('author', 'like', "%{$value}%");
    }
}
