<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use App\Services\FilterManager;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function __construct(
        private LeaveRequestService $service,
        private FilterManager $filterManager
    ) {
        $this->filterManager = app('leave_request_filter_manager');
    }

    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee', 'stage', 'approver']);

        $query = $this->filterManager->apply($query, $request);

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
