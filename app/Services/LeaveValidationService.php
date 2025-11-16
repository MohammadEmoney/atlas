<?php 

namespace App\Services;

use App\Models\Employee;

class LeaveValidationService
{
    public function __construct(private array $validators){}

    public function validate(Employee $employee, $payload): array
    {
        $errors = [];

        foreach($this->validators as $validator){
            $validatorErrors = $validator->validate($employee, $payload);
            $errors = array_merge($errors, $validatorErrors);
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
        ];
    }
}