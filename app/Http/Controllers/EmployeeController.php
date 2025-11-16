<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\FilterManager;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/employees',
    description: 'Get a list of employees with optional filtering',
    tags: ['Employees'],
    parameters: [
        new OA\Parameter(
            name: 'position',
            description: 'Filter employees by position',
            in: 'query',
            schema: new OA\Schema(type: 'string')
        ),
        new OA\Parameter(
            name: 'manager_id',
            description: 'Filter employees by manager ID',
            in: 'query',
            schema: new OA\Schema(type: 'integer')
        ),
        new OA\Parameter(
            name: 'q',
            description: 'Search employees by name or email',
            in: 'query',
            schema: new OA\Schema(type: 'string')
        ),
        new OA\Parameter(
            name: 'per_page',
            description: 'Number of employees per page (default: 10)',
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
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'full_name', type: 'string', example: 'John Doe'),
                                new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
                                new OA\Property(property: 'position', type: 'string', example: 'Software Engineer', nullable: true),
                                new OA\Property(property: 'leave_balance', type: 'integer', example: 20),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2025-11-16T00:00:00.000000Z'),
                                new OA\Property(
                                    property: 'manager',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'full_name', type: 'string', example: 'Jane Smith'),
                                    ],
                                    type: 'object',
                                    nullable: true
                                ),
                            ],
                            type: 'object'
                        )
                    ),
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
class EmployeeController extends Controller
{
    public function __construct(private FilterManager $filterManager){
        $this->filterManager = app('employee_filter_manager');
    }

    /**
     * Get a list of employees with optional filtering
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        $query = $this->filterManager->apply($query, $request);

        $perPage = (int) $request->get('per_page', 10);

        $data = $query->with('manager')->paginate($perPage);

        return EmployeeResource::collection($data)->response();
    }
}
