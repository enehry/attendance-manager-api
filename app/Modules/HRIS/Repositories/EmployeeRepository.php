<?php

declare(strict_types=1);

namespace App\Modules\HRIS\Repositories;

use App\Modules\HRIS\Models\Employee;
use App\Shared\Abstracts\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Employee Repository
 *
 * Purpose: Handles all database interactions for the Employee domain.
 * This separates Eloquent/DB logic from Controllers and Services.
 */
class EmployeeRepository extends BaseRepository
{
    public function model(): string
    {
        return Employee::class;
    }

    public function getActive()
    {
        return $this
            ->newQuery()
            ->active()
            ->orderBy('last_name')
            ->get();
    }

    public function getByNumber(string $employeeNumber): ?Employee
    {
        return $this
            ->newQuery()
            ->where('employee_number', $employeeNumber)
            ->first();
    }

    public function paginateWithFilters(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        return $this
            ->newQuery()
            ->with(['department', 'schedule', 'user'])
            ->when(isset($filters['employee_status']), fn ($q) => $q->where('employee_status', $filters['employee_status'])
            )
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $query->where('full_name', 'ilike', "%{$filters['search']}%");
            })
            ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_order'] ?? 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }
}
