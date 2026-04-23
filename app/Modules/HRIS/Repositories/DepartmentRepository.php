<?php

namespace App\Modules\HRIS\Repositories;

use App\Modules\HRIS\Models\Department;
use App\Shared\Abstracts\BaseRepository;
use Illuminate\Support\Collection;

class DepartmentRepository extends BaseRepository
{
    protected function model(): string
    {
        return Department::class;
    }

    public function getAllActive(): Collection
    {
        return $this->newQuery()
            ->orderBy('name')
            ->get();
    }
}
