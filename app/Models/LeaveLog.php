<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveLog extends Model
{
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'leave_request_id',
        'action',
        'performed_by',
        'meta',
    ];

    /**
    * The attributes that should be cast.
    *
    * @var array
    */
    protected $casts = [
        'meta' => 'json'
    ];

    /**
     * Get the leaveRequest that owns the LeaveLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    /**
     * Get the performer that owns the LeaveLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'performed_by');
    }
}
