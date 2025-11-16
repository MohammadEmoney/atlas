<?php 

namespace App\Services;

use App\Filters\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FilterManager
{
    private array $filters = [];

    public function __construct(FilterInterface ...$filters) {
        $this->filters = $filters;
    }

    public function addFilters(FilterInterface $filter): self
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