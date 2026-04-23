<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\HRIS\Models\Department;
use App\Modules\HRIS\Models\Employee;
use App\Modules\Schedule\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $total = 10000;
        $chunkSize = 1000;

        $departments = Department::pluck('id');
        $schedules = Schedule::pluck('id');

        $this->command->info("Seeding {$total} employees in chunks of {$chunkSize}...");

        for ($i = 0; $i < $total; $i += $chunkSize) {
            DB::transaction(function () use ($chunkSize, $departments, $schedules) {
                // 1. Bulk create Users using factory but returning a collection
                // Note: factory()->count()->create() is faster than a loop but still performs individual inserts.
                // For TRUE bulk, we'd use DB::table('users')->insert(), but we need the IDs.
                $users = User::factory($chunkSize)->create();

                $employeeData = [];
                $now = now();

                foreach ($users as $user) {
                    $firstName = fake()->firstName();
                    $lastName = fake()->lastName();

                    $employeeData[] = [
                        'uuid' => (string) Str::uuid(),
                        'employee_number' => $now->year.'-'.fake()->unique()->numerify('##########'),
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'address' => fake()->address(),
                        'employment_status' => fake()->randomElement(['permanent', 'casual', 'contractual']),
                        'user_id' => $user->id,
                        'department_id' => $departments->random(),
                        'schedule_id' => $schedules->random(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // 2. Bulk insert Employees in one query per chunk
                Employee::insert($employeeData);
            });

            $this->command->comment('Chunk '.(($i / $chunkSize) + 1).' completed.');
        }
    }
}
