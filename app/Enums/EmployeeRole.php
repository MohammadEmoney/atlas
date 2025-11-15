<?php 

namespace App\Enums;

enum EmployeeRole: string {
    case Employee = 'employee';
    case Manager = 'manager';
    case HR = 'hr';
    case CEO = 'ceo';
}