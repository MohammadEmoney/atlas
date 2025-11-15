<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee' => new EmployeeResource($this->whenLoaded('employee') ?? $this->employee),
            'approver' => $this->whenLoaded('approver', fn() => ['id' => $this->approver?->id, 'full_name' => $this->approver?->full_name]),
            'start_date' => $this->start_date?->toDateString,
            'end_date' => $this->end_date?->toDateString,
            'start_time' => $this->start_time,
            'reason' => $this->reason,
            'leave_type' => $this->leave_type,
            'status' => $this->status,
            'stage' => $this->whenLoaded('stage', fn() => ['id' => $this->stage?->id, 'name' => $this->stage?->name]),
            'rejection_reason' => $this->rejection_reason,
            'days_count' => $this->days_count,
            'logs' => $this->whenLoaded('logs', fn() => $this->logs->map(fn($l) => [
                'action' => $l->action,
                'performed_by' => $l->performedBy,
                'meta' => $l->meta,
                'created_at' => $l->created_at
            ])),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
