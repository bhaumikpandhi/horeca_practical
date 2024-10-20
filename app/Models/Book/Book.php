<?php

namespace App\Models\Book;

use App\Filters\Book\BookFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'published_date',
        'genre_id'
    ];

    /**
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request): Builder
    {
        return (new BookFilter($request))->filter($builder);
    }

    /**
     * @return BelongsTo
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
