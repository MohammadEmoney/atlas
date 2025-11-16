<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface EmployeeFilterInterface
{
    public function apply(Builder $query, Request $request): Builder;
    public function shouldApply(Request $request): bool;
}