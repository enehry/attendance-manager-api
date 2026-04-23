<?php

declare(strict_types=1);

namespace App\Modules\HRIS\Services;

use App\Modules\HRIS\DTOs\EmployeeData;
use App\Modules\HRIS\Events\EmployeeCreated;
use App\Modules\HRIS\Events\EmployeeUpdated;
use App\Modules\HRIS\Models\Employee;
use App\Modules\HRIS\Repositories\EmployeeRepository;
use App\Shared\Contracts\EmployeeDataContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\DataCollection;

class EmployeeService implements EmployeeDataContract
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly EmployeeRepository $repository) {}

    public function getActiveEmployees(): DataCollection
    {
        return EmployeeData::collect($this->repository->getActive());
    }

    public function getEmployeeById(string $id): EmployeeData
    {
        $employee = $this->repository->findByIdOrFail($id);

        return EmployeeData::from($employee);
    }

    public function getEmployeeByNumber(string $employeeNumber): EmployeeData
    {
        $employee = $this->repository->getByNumber($employeeNumber);
        abort_unless($employee, 404, "Employee not found: {$employeeNumber}");

        return EmployeeData::from($employee);
    }

    public function employeeExists(string $id): bool
    {
        return $this->repository->findById($id) !== null;
    }

    public function createEmployee(EmployeeData $data): EmployeeData
    {
        $employee = $this->repository->create($data->toArray());

        return EmployeeData::from($employee);
    }

    public function create(array $data): Employee
    {
        $data['employee_number'] = $this->generateEmployeeNumber();
        $data['user_id'] = Auth::user()->id;

        $employee = $this->repository->create($data);

        event(new EmployeeCreated($employee));

        return $employee;
    }

    public function update(string $id, array $data): Employee
    {
        $employee = $this->repository->update($id, $data);

        event(new EmployeeUpdated($employee));

        return $employee;
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function forceDelete(string $id): bool
    {
        return $this->repository->forceDelete($id);
    }

    public function restore(string $id): bool
    {
        return $this->repository->restore($id);
    }

    public function paginateWithFilters(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        return $this->repository->paginateWithFilters($filters, $perPage);
    }

    private function generateEmployeeNumber(): string
    {
        $year = now()->format('Y');
        $count = (string) (Employee::whereYear('created_at', $year)->count() + 1);

        return $year.'-'.str_pad($count, 8, '0', STR_PAD_LEFT);
    }
}
