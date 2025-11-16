<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Atlas API Documentation",
    description: "API documentation for the Atlas employee management system"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local development server"
)]
#[OA\Tag(
    name: "Employees",
    description: "Operations related to employees"
)]
#[OA\Tag(
    name: "Leave Requests",
    description: "Operations related to leave requests"
)]
class ApiDocumentation
{
    // This class is just for documentation purposes
}