<?php 

namespace App\Strategies\LeaveValidation;

use App\Models\Employee;

class DurationValidator implements LeaveValidationStrategy
{
    public function validate(Employee $employee,array $payload): array
    {
        $errors = [];

        $daysCount = $payload['days_count'] ?? 0;
        $hoursCount = $payload['hours_count'] ?? 0;

        if($payload['leave_type'] !== 'hourly' && $daysCount > 30){
            $errors[] = 'Each leave request cannot exceed 30 days.';
        }

        if($payload['leave_type'] !== 'hourly' &&  $hoursCount > 8){
            $errors[] = 'Each leave request cannot exceed 8 hours.';
        }

        return $errors;
    }
}