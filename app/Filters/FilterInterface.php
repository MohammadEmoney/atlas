<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface FilterInterface
{
    public function apply(Builder $query, Request $request): Builder;
    public function shouldApply(Request $request): bool;
}