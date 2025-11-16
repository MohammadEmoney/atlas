<?php 

namespace App\Strategies\LeaveValidation;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class TimeOverlapValidator implements LeaveValidationStrategy
{
    public function validate(Employee $employee,array $payload): array
    {
        $errors = [];

        $startDate = Carbon::parse($payload['start_date']);
        $endDate = Carbon::parse($payload['end_date']);

        $overlapingRequests = LeaveRequest::where('employee_id', $employee->id)
            ->whereIn('status', ['pending', 'approved', 'pending_hr', 'pending_ceo', 'pending_manager'])
            ->where(function($query) use ($startDate, $endDate){
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere('start_date', '<=', $startDate)
                    ->orWhere('end_date', '>=', $endDate);
            })->exists();

        if($overlapingRequests){
            $errors[] = 'The leave request conflicts with previous dates.';
        }

        return $errors;
    }
}