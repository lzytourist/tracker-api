<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class StartDateFilter
{
    public function handle(Builder $builder, Closure $next)
    {
        $start = request()->query->get('start_date');

        if (!$start) {
            return $next($builder);
        }

        $builder = $next($builder);
        return $builder->whereDate('created_at', '>=', $start);
    }
}
