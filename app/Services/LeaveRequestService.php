<?php 

namespace App\Services;

use App\Enums\LeaveStatus;
use App\Models\Employee;
use App\Models\LeaveLog;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveRequestService
{

    public function __construct(private LeaveValidationService $validationService){}

    public function calculdateDaysCount(array $payload): int
    {
        $start = Carbon::parse($payload['start_date']);
        $end = Carbon::parse($payload['end_date']);
        $days = $start->diffInDays($end);
        return (int) $days;
    }

    public function calculateHoursCount(array $payload)
    {
        if($payload !== 'hourly'){
            return null;
        }
        $start = Carbon::parse($payload['start_time']);
        $end = Carbon::parse($payload['end_time']);
        $hours = $start->diffInHours($end);
        return (int) $hours;
    }

    public function create(array $payload, $actorId)
    {
        return DB::transaction(function () use ($payload, $actorId) {
            $days = $this->calculdateDaysCount($payload);
            $hours = $this->calculateHoursCount($payload);

            $employee = Employee::findOrFail($payload['employee_id']);

            $data = array_merge($payload,['days_count' => $days, 'hours_count' => $hours]);
            $validatonResult = $this->validationService->validate($employee, $data);

            // Defining status based on leave validations
            if(!$validatonResult['is_valid'] && !empty($validatonResult['errors'])){
                // If there is any error it will be saved as draft.
                $status = LeaveStatus::Draft->value;
                $rejectionReason = implode(' ', $validatonResult['errors']);
            }else{
                // Implementing Stage service
                $status = LeaveStatus::PendingHR->value;
                $rejectionReason = null;
            }


            $request = LeaveRequest::create(array_merge($payload, [
                'days_count' => $days,
                'hours_count' => $hours,
                'status' => LeaveStatus::Draft->value,
                'rejection_reason' => $rejectionReason
            ]));

            LeaveLog::create([
                'leave_request_id' => $request->id,
                'action' => 'created',
                'preformed_by' => $actorId,
                'meta' => ['days_count' => $days, 'hours_count' => $hours, 'errors' => $validatonResult['errors']]
            ]);

            return $request;
        });
    }

    public function approve($leaveRequet, int $approverId, ?string $comment = null)
    {
        return DB::transaction(function() use ($leaveRequet, $approverId, $comment){
            $leaveRequet->log()->create([
                'action' => 'approved',
                'performed_by' => $approverId,
                'meta' => ['comment' => $comment]
            ]);

            $currentStage = $leaveRequet->stage;
            $nextSrage = $currentStage?->nextStage;

            if($nextSrage){
                $leaveRequet->update([
                    'approver_id' => $approverId,
                    'stage_id' => $nextSrage->id,
                    'status' => LeaveStatus::Approved->value
                ]);
            }else{
                // Final Approve
                $leaveRequet->update([
                    'approver_id' => $approverId,
                    'status' => LeaveStatus::Approved->value
                ]);   
            }

            return $leaveRequet->fresh();
        });
    }

    public function reject($leaveRequet, int $approverId, string $reason)
    {
        return DB::transaction(function() use ($leaveRequet, $approverId, $reason){
            $leaveRequet->log()->create([
                'action' => 'approved',
                'performed_by' => $approverId,
                'meta' => ['reson' => $reason]
            ]);

            $leaveRequet->update([
                'approver_id' => $approverId,
                'status' => LeaveStatus::Rejected->value,
                'rejection_reason' => $reason,
            ]);

            return $leaveRequet->fresh();
        });
    }
}