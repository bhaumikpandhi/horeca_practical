<?php

namespace App\Filters\Book\Apply;

use Illuminate\Database\Eloquent\Builder;

class GenreFilter
{
    /**
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function filter(Builder $builder, $value)
    {
        return $builder->whereHas('genre', function ($query) use ($value) {
            $query->where('name', $value);
        });
    }
}
