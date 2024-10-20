<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

abstract class BaseFilter
{
    protected $request;
    protected array $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function filter(Builder $builder)
    {
        $this->getFilters()->map(function ($value, $filter) use ($builder) {
            $this->resolveFilter($filter)->filter($builder, $value);
        });
        return $builder;
    }

    /**
     * @return Collection
     */
    protected function getFilters()
    {
        return collect($this->request->filter)->filter(function ($value, $key) {
            return collect($this->filters)->has($key);
        });
    }

    /**
     * @param $filter
     * @return mixed
     */
    protected function resolveFilter($filter)
    {
        return new $this->filters[$filter];
    }
}
