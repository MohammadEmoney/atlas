<?php

use App\Models\Employee;

test('return paginated employees', function(){
    Employee::factory(15)->create();
    $response = $this->get('api/employees?per_page=5');
    $response->assertStatus(200);
    $data = $response->json();
    expect($data['data'])->toHaveCount(5);
    expect($data['meta']['per_page'])->toBe(5);
});
