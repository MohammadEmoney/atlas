<?php 

namespace App\Filters;

use Illuminate\Http\Request;

abstract class AbstractFilter implements FilterInterface
{
    public function shouldApply(Request $request): bool
    {
        return $request->has($this->getFilterKey());   
    }

    abstract protected function getFilterKey(): string;
}