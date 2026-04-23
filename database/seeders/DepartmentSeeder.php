<?php

namespace Database\Seeders;

use App\Modules\HRIS\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Office of the Mayor', 'code' => 'MAYOR', 'description' => 'Executive office of the local government.'],
            ['name' => 'Office of the Vice Mayor', 'code' => 'OVM', 'description' => 'Office of the legislative presiding officer.'],
            ['name' => 'Sangguniang Bayan', 'code' => 'SB', 'description' => 'The legislative body of the municipality.'],
            ['name' => 'Human Resource Management Office', 'code' => 'HRMO', 'description' => 'Handles personnel recruitment, management, and benefits.'],
            ['name' => 'Management Information System', 'code' => 'MIS', 'description' => 'Handles IT infrastructure and software systems.'],
            ['name' => 'Accounting Office', 'code' => 'ACCT', 'description' => 'Handles financial records and transactions.'],
            ['name' => 'General Services Office', 'code' => 'GSO', 'description' => 'Handles procurement and facility maintenance.'],
            ['name' => 'Municipal Planning and Development Office', 'code' => 'MPDO', 'description' => 'Responsible for urban planning and development.'],
            ['name' => 'Municipal Civil Registrar', 'code' => 'MCR', 'description' => 'Handles birth, marriage, and death certifications.'],
            ['name' => 'Municipal Budget Office', 'code' => 'MBO', 'description' => 'Manages the municipal budget and allocations.'],
            ['name' => 'Municipal Treasurer\'s Office', 'code' => 'MTO', 'description' => 'Handles tax collection and treasury management.'],
            ['name' => 'Municipal Assessor\'s Office', 'code' => 'ASSESSOR', 'description' => 'Responsible for real property assessment.'],
            ['name' => 'Municipal Health Office', 'code' => 'MHO', 'description' => 'Provides public health services.'],
            ['name' => 'Municipal Social Welfare and Development Office', 'code' => 'MSWDO', 'description' => 'Provides social welfare services.'],
            ['name' => 'Municipal Agriculture Office', 'code' => 'AGRI', 'description' => 'Supports local farmers and agricultural programs.'],
            ['name' => 'Municipal Engineering Office', 'code' => 'ENGR', 'description' => 'Handles infrastructure projects and building permits.'],
            ['name' => 'Business Permit and Licensing Office', 'code' => 'BPLO', 'description' => 'Handles business registration and licensing.'],
            ['name' => 'Disaster Risk Reduction and Management Office', 'code' => 'DRRMO', 'description' => 'Manages emergency response and disaster preparedness.'],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
