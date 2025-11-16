<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PositionFilter extends AbstractEmployeeFilter
{
    protected function getFilterKey(): string
    {
        return 'position';
    }

    public function apply(Builder $query, Request $request): Builder
    {
        return $query->where('position', $request->get('position'));
    }
}