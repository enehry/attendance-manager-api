<?php

namespace Database\Seeders;

use App\Modules\HRIS\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::factory(50)->create();
    }
}
