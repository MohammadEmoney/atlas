<?php

use App\Filters\AbstractEmployeeFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeFilter extends AbstractEmployeeFilter
{
    private string $filterKey;

    public function __construct(string $filterKey)
    {
        $this->filterKey = $filterKey;
    }

    protected function getFilterKey(): string
    {
        return $this->filterKey;
    }

    public function apply(Builder $query, Request $request): Builder
    {
        return $query;
    }
}

test('AbstractEmployeeFilter shouldApply returns true when request has filter key', function () {
    $filterKey = 'test_key';
    $filter = new EmployeeFilter($filterKey);
    
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('has')->with($filterKey)->andReturn(true);
    
    $result = $filter->shouldApply($request);
    
    expect($result)->toBeTrue();
});

test('AbstractEmployeeFilter shouldApply returns false when request does not have filter key', function () {
    $filterKey = 'test_key';
    $filter = new EmployeeFilter($filterKey);
    
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('has')->with($filterKey)->andReturn(false);
    
    $result = $filter->shouldApply($request);
    
    expect($result)->toBeFalse();
});