<?php 

namespace App\Filters\LeaveRequest;

use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StatusFilter extends AbstractFilter
{
    protected function getFilterKey(): string
    {
        return 'status';
    }

    public function apply(Builder $query, Request $request): Builder
    {
        return $query->where('status', $request->get('status'));
    }
}