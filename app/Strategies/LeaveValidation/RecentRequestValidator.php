<?php 

namespace App\Strategies\LeaveValidation;

use App\Models\Employee;
use App\Models\LeaveRequest;

class RecentRequestValidator implements LeaveValidationStrategy
{
    public function validate(Employee $employee,array $payload): array
    {
        $errors = [];

        $lastRequest = LeaveRequest::where('employee_id', $employee->id)
            ->whereIn('status', ['pending', 'approved', 'pending_hr', 'pending_ceo', 'pending_manager'])
            ->orderBy('created_at', 'desc')->first();

        if($lastRequest){
            $errors[] = 'It has been less than 3 days since your last leave request.';
        }

        return $errors;
    }
}