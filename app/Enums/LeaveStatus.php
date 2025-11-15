<?php 

namespace App\Enums;

enum LeaveStatus: string {
    case Draft = 'draft';
    case PendingHR = 'pending_hr';
    case PendingManager = 'pending_manager';
    case PendingCEO = 'pending_ceo';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case DueDate = 'due_date';
}