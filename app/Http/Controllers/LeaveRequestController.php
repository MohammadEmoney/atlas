<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveLeaveRequest;
use App\Http\Requests\RejectLeaveRequest;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use App\Services\FilterManager;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/leave-requests',
    description: 'Get a list of leave requests with optoinal filtering',
    tags: ['Leave Requests'],
    parameters: [
        new OA\Parameter(
            name: 'employee_id',
            description: 'Filter leave requests by employee id',
            in: 'query',
            schema: new OA\Schema(type: 'integer')
        ),
        new OA\Parameter(
            name: 'status',
            description: 'Filter leave requests by status',
            in: 'query',
            schema: new OA\Schema(type: 'string')
        ),
        new OA\Parameter(
            name: 'per_page',
            description: 'Number of leave requests per page (default: 10)',
            in: 'query',
            schema: new OA\Schema(type: 'integer', default: 10)
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Successful response',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(
                                property: 'employee',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'full_name', type: 'string', example: 'Mohammad Imani'),
                                    new OA\Property(property: 'email', type: 'string', example: 'm.imani@example.com'),
                                    new OA\Property(property: 'position', type: 'string', example: 'Web Developer', nullable: true),
                                    new OA\Property(property: 'leave_balance', type: 'integer', example: 20),
                                    // Rest of the properties
                                ],
                                type: 'object'
                            ),
                        ],
                        type: 'object'
                    )),
                    new OA\Property(property: 'links', type: 'object', properties: [
                        new OA\Property(property: 'first', type: 'string'),
                        new OA\Property(property: 'last', type: 'string'),
                        new OA\Property(property: 'prev', type: 'string', nullable: true),
                        new OA\Property(property: 'next', type: 'string', nullable: true),
                    ]),
                    new OA\Property(property: 'meta', type: 'object', properties: [
                        new OA\Property(property: 'current_page', type: 'integer'),
                        new OA\Property(property: 'from', type: 'integer'),
                        new OA\Property(property: 'last_page', type: 'integer'),
                        new OA\Property(property: 'links', type: 'array', items: new OA\Items(
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'url', type: 'string', nullable: true),
                                new OA\Property(property: 'label', type: 'string'),
                                new OA\Property(property: 'active', type: 'boolean'),
                            ]
                        )),
                        new OA\Property(property: 'path', type: 'string'),
                        new OA\Property(property: 'per_page', type: 'integer'),
                        new OA\Property(property: 'to', type: 'integer'),
                        new OA\Property(property: 'total', type: 'integer'),
                    ]),
                ]
            )
        ),
        new OA\Response(response: 401, description: 'Unauthorized'),
    ]
)]
#[OA\Post(
    path: '/api/leave-requests',
    description: 'Create a new leave request',
    tags: ['Leave Requests'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['employee_id', 'leave_type', 'start_date', 'end_date'],
                properties: [
                    new OA\Property(property: 'employee_id', type: 'integer', description: 'ID of the employee requesting leave', example: 1),
                    // Rest of the properties
                ]
            )
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Leave request created successfully',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    // Rest of the properties
                ],
                type: 'object'
            )
        ),
        new OA\Response(response: 401, description: 'Unauthorized'),
        new OA\Response(response: 422, description: 'Validation error'),
    ]
)]
class LeaveRequestController extends Controller
{
    public function __construct(
        private LeaveRequestService $service,
        private FilterManager $filterManager
    ) {
        $this->filterManager = app('leave_request_filter_manager');
    }

    /**
     * Get a list of leave requests with optional filtering
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee', 'stage', 'approver']);

        $query = $this->filterManager->apply($query, $request);

        $data = $query->paginate($request->get('per_page', 10));

        return LeaveRequestResource::collection($data)->response();
    }

    /**
     * Create a new leave request
     */
    public function store(StoreLeaveRequest $request)
    {
        $payload = $request->validated();
        $leaveRequest =  $this->service->create($payload, $payload['employee_id']);

        return (new LeaveRequestResource($leaveRequest))->response()->setStatusCode(201);
    }

    /**
     * Approve a leave request
     */
    public function approve(ApproveLeaveRequest $req, LeaveRequest $leaveRequest)
    {
        $payload = $req->validated();
        $leaveRequest = $this->service->approve($leaveRequest, $payload['approver_id'], $payload['comment'] ?? null);
        return new LeaveRequestResource($leaveRequest);
    }

    /**
     * Approve a leave request
     */
    public function reject(RejectLeaveRequest $req, LeaveRequest $leaveRequest)
    {
        $payload = $req->validated();
        $leaveRequest = $this->service->reject($leaveRequest, $payload['approver_id'], $payload['reason'] ?? null);
        return new LeaveRequestResource($leaveRequest);
    }
}
