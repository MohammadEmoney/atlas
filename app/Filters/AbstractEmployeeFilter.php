<?php 

namespace App\Filters;

use Illuminate\Http\Request;

abstract class AbstractEmployeeFilter implements EmployeeFilterInterface
{
    public function shouldApply(Request $request): bool
    {
        return $request->has($this->getFilterKey());   
    }

    abstract protected function getFilterKey(): string;
}