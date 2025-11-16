<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ManagerFilter extends AbstractEmployeeFilter
{
    protected function getFilterKey(): string
    {
        return 'manager_id';
    }

    public function apply(Builder $query, Request $request): Builder
    {
        return $query->where('manager_id', $request->get('manager_id'));
    }
}