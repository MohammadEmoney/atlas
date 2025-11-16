<?php

namespace Database\Seeders;

use App\Models\Employee;
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
    }
}
