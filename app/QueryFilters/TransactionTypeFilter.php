<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class TransactionTypeFilter
{
    public function handle(Builder $builder, Closure $next)
    {
        $transactionType = request()->query->get('transaction_type');

        if (!in_array($transactionType, ['BALANCE', 'EXPENSE', 'LOAN_TAKEN', 'LOAN_GIVEN'])) {
            return $next($builder);
        }

        $builder = $next($builder);
        return $builder->where('transaction_type', '=', $transactionType);
    }
}
