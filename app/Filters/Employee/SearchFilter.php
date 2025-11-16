<?php 

namespace App\Filters\Employee;

use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchFilter extends AbstractFilter
{
    protected function getFilterKey(): string
    {
        return 'q';
    }

    public function apply(Builder $query, Request $request): Builder
    {
        $searchTerm = $request->get('q');

        return $query->where(function($query) use($searchTerm){
            $query->where('full_name', 'LIKE', "%$searchTerm%")
                ->orWhere('email', 'LIKE', "%$searchTerm%");
        });
    }
}