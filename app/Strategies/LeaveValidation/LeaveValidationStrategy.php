<?php 

namespace App\Strategies\LeaveValidation;

use App\Models\Employee;

interface LeaveValidationStrategy
{
    public function validate(Employee $employee, array $payload): array;
}