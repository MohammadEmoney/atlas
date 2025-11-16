<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Stage;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $ceo = Employee::factory()->ceo()->create([
            'full_name' => 'CEO Example',
            'email' => 'ceo@example.com',
        ]);

        $hr = Employee::factory()->hr()->create([
            'full_name' => 'HR User',
            'email' => 'hr@example.com',
        ]);

        $manager = Employee::factory()->manager()->create([
            'full_name' => 'Manger One',
            'email' => 'manager@example.com',
        ]);


        Employee::factory(10)->create(['manager_id' => $manager->id]);

        //Stage Seeder
        $hrStage = Stage::create(['name' => 'HR Review', 'role' => 'hr', 'order' => 1, 'min_days' => 0]);
        $managerStage = Stage::create(['name' => 'Manager Review', 'role' => 'manager', 'order' => 2, 'min_days' => 0]);
        $ceoStage = Stage::create(['name' => 'CEO Approval', 'role' => 'ceo', 'order' => 3, 'min_days' => 5]);

        $hrStage->update(['next_stage_id' => $managerStage->id]);
        $managerStage->update(['next_stage_id' => $ceoStage->id]);
    }
}
