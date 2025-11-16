<?php 

namespace App\Filters\Employee;

use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ManagerFilter extends AbstractFilter
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