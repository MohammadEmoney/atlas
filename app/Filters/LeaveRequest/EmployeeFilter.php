<?php 

namespace App\Filters\LeaveRequest;

use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeFilter extends AbstractFilter
{
    protected function getFilterKey(): string
    {
        return 'employee_id';
    }

    public function apply(Builder $query, Request $request): Builder
    {
        return $query->where('employee_id', $request->get('employee_id'));
    }
}