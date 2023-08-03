<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class EndDateFilter
{
    public function handle(Builder $builder, Closure $next)
    {
        $end = request()->query->get('end_date');

        if (!$end) {
            return $next($builder);
        }

        $builder = $next($builder);
        return $builder->whereDate('created_at', '<=', $end);
    }
}
