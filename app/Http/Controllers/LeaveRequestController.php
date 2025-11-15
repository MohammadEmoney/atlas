<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function __construct(private LeaveRequestService $service) {}

    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee', 'stage', 'approver']);

        $query->when($request->get('employee_id'), function ($query, $employeeId) {
            $query->where('employee_id', $employeeId);
        });

        $query->when($request->get('status'), function ($query, $status) {
            $query->where('status', $status);
        });

        $data = $query->paginate($request->get('per_page', 10));

        return LeaveRequestResource::collection($data)->response();
    }

    public function store(StoreLeaveRequest $request)
    {
        $payload = $request->validated();
        $leaveRequest =  $this->service->create($payload, $payload['employee_id']);

        return (new LeaveRequestResource($leaveRequest))->response()->setStatusCode(201);
    }
}
