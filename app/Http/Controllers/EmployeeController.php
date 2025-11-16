<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeFilterManager;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    public function __construct(private EmployeeFilterManager $filterManager){}

    public function index(Request $request)
    {
        $query = Employee::query();

        $query = $this->filterManager->apply($query, $request);
        
        $perPage = (int) $request->get('per_page', 10);

        $data = $query->with('manager')->paginate($perPage);

        return EmployeeResource::collection($data)->response();
    }
}
