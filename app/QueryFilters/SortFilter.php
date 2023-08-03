<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class SortFilter
{
    public function handle(Builder $builder, Closure $next)
    {
        $sortDirection = strtoupper(request()->query->get('sort'));

        if (!in_array($sortDirection, ['ASC', 'DESC'])) {
            return $next($builder);
        }

        $builder = $next($builder);
        return $builder->orderBy('created_at', $sortDirection);
    }
}
