<?php 

namespace App\Services;

use App\Filters\EmployeeFilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeFilterManager
{
    private array $filters = [];

    public function __construct(EmployeeFilterInterface ...$filters) {
        $this->filters = $filters;
    }

    public function addFilters(EmployeeFilterInterface $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function apply(Builder $query, Request $request)
    {
        foreach($this->filters as $filter){
            if($filter->shouldApply($request)){
                $query = $filter->apply($query, $request);
            }
        }

        return $query;
    }
}