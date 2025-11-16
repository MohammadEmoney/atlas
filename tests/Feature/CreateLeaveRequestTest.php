<?php

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('creates a leave request and starts approval', function(){
    $this->seed();
    $employee = Employee::factory()->create();

    $payload = [
        'employee_id' => $employee->id,
        'leave_type' => 'annual',
        'start_date' => now()->addDays(7)->toDateString(),
        'end_date' => now()->addDays(9)->toDateString(),
        'reason' => 'vacation',
    ];

    $response = $this->post('api/leave-requests', $payload);
    $response->assertStatus(201);
    $json = $response->json();
    expect($json['data']['status'])->not()->toBeNull();
});
