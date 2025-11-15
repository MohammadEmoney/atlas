<?php 

namespace App\Services;

use App\Enums\LeaveStatus;
use App\Models\LeaveLog;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveRequestService
{
    public function calculdateDaysCount(array $payload): int
    {
        $start = Carbon::parse($payload['start_date']);
        $end = Carbon::parse($payload['end_date']);
        $days = $start->diffInDays($end) + 1;
        return (int) $days;
    }

    public function create(array $payload, $actorId)
    {
        return DB::transaction(function () use ($payload, $actorId) {
            $days = $this->calculdateDaysCount($payload);

            $request = LeaveRequest::create(array_merge($payload, [
                'days_count' => $days,
                'status' => LeaveStatus::Draft->value,
            ]));

            LeaveLog::create([
                'leave_request_id' => $request->id,
                'action' => 'created',
                'preformed_by' => $actorId,
                'meta' => ['days_count' => $days]
            ]);

            return $request;
        });
    }
}