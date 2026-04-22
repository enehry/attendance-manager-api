<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\HRIS\Models\Employee;
use App\Modules\Schedule\Models\Schedule;
use App\Shared\Enums\EmploymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'employee_number' => $this->faker->unique()->numerify('2024-####'),
            'last_name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'employment_status' => $this->faker->randomElement(EmploymentStatus::cases()),
            'user_id' => User::factory(),
            'schedule_id' => Schedule::factory(),
        ];
    }
}
