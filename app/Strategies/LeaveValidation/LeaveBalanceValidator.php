<?php 

namespace App\Strategies\LeaveValidation;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class LeaveBalanceValidator implements LeaveValidationStrategy
{
    public function validate(Employee $employee,array $payload): array
    {
        $errors = [];

        $leaveType = $payload['leave_type'];
        $daysCount = $payload['days_count'] ?? 0;
        $hoursCount = $payload['hours_count'] ?? 0;

        match ($leaveType) {
            'annual' => $this->validateAnnaulLeave($employee, $daysCount, $payload, $errors),
            'sick' => $this->validateSickLeave($employee, $daysCount, $payload, $errors),
            'hourly' => $this->validateHourlyLeave($employee, $hoursCount, $payload, $errors),
            'unpaid' => null,
            'default' => null,
        };

        return $errors;
    }

    private function validateAnnaulLeave(Employee $employee, float $daysCount, array $payload, array &$errors)
    {
        if($employee->leave_balance < $daysCount){
            $errors[] = 'Your leave balance is insufficient';
        }

        $monthlyUsed = $this->getMonthlyUsed($employee->id, $daysCount, $payload['start_date']);

        if($monthlyUsed + $daysCount > 2.5){
            $errors[] = 'The maximum leave per month is 2.5 days';
        }
    }

    private function validateSickLeave(Employee $employee, float $daysCount, array $payload, array &$errors)
    {
        if($employee->leave_balance < $daysCount){
            $errors[] = 'Your paid leave balance is insufficient';
        }

        $monthlyUsed = $this->getMonthlyUsed($employee->id, $daysCount, $payload['start_date']);

        if($monthlyUsed + $daysCount > 5){
            $errors[] = 'The maximum sick leave per month is 5 days';
        }
    }

    private function validateHourlyLeave(Employee $employee, float $hoursCount, array $payload, array &$errors)
    {
        if($employee->leave_balance < $hoursCount){
            $errors[] = 'Your hourly leave balance is insufficient';
        }

        $monthlyUsed = $this->getMonthlyUsed($employee->id, 'hourly', $payload['start_date']);

        if($monthlyUsed + $hoursCount > 20){
            $errors[] = 'The maximum hourly leave per month is 20 hours';
        }
    }

    private function getMonthlyUsed(int $employeeId, string $leaveType, string $startDate):float
    {
        $start = Carbon::parse($startDate);
        $monthStart = $start->copy()->startOfMonth();
        $monthEnd = $start->copy()->endOfMonth();

        return LeaveRequest::where('employee_id', $employeeId)
            ->where('leave_type', $leaveType)
            ->whereIn('status', ['approved', 'pending_hr', 'pending_manager', 'pendinf_ceo'])
            ->whereBetween('start_date', [$monthStart, $monthEnd])
            ->sum('leave_type' === 'hourly' ? 'hours_count' : 'days_count');
    }
}