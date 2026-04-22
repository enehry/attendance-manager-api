<?php

namespace Database\Seeders;

use App\Modules\Schedule\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::factory()->create([
            'name' => 'Regular Shift',
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
        ]);

        Schedule::factory()->create([
            'name' => 'Morning Shift',
            'start_time' => '06:00:00',
            'end_time' => '15:00:00',
        ]);

        Schedule::factory()->create([
            'name' => 'Afternoon Shift',
            'start_time' => '13:00:00',
            'end_time' => '22:00:00',
        ]);
    }
}
