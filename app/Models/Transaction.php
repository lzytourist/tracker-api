<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'amount',
        'details',
        'transaction_type'
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('user_resource', function (Builder $builder) {
            $builder->where('user_id', '=', request()->user()->id);
        });
    }
}
