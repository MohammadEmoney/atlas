<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();


        $query->when($request->get('q'), function($query, $q){
            $query->where('full_name', 'LIKE', "%$q%")
                ->orWhere('email', 'LIKE', "%$q%");
        });

        $query->when($request->get('position'), function($query, $position){
            $query->where('position', $position);
        });

        $query->when($request->get('manager_id'), function($query, $managerId){
            $query->where('manager_id', $managerId);
        });
            
        $perPage = (int) $request->get('per_page', 10);

        $data = $query->with('manager')->paginate($perPage);

        return EmployeeResource::collection($data)->response();
    }
}
