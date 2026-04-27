<?php

declare(strict_types=1);

namespace App\Modules\HRIS\Services;

use App\Models\User;
use App\Modules\HRIS\DTOs\EmployeeData;
use App\Modules\HRIS\Events\EmployeeCreated;
use App\Modules\HRIS\Events\EmployeeUpdated;
use App\Modules\HRIS\Models\Employee;
use App\Modules\HRIS\Repositories\EmployeeRepository;
use App\Shared\Contracts\EmployeeDataContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\DataCollection;

class EmployeeService implements EmployeeDataContract
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly EmployeeRepository $repository) {}

    public function getById(string $id): Employee
    {
        return $this->repository->findByIdOrFail($id);
    }

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
        return DB::transaction(function () use ($data) {
            $data['employee_number'] = $this->generateEmployeeNumber();

            // Create user account for the employee
            $user = User::create([
                'name' => "{$data['first_name']} {$data['last_name']}",
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $data['user_id'] = $user->id;

            if (isset($data['profile_photo'])) {
                $path = $data['profile_photo']->store('employees', 'local');
                $data['profile_photo_url'] = $path;
                unset($data['profile_photo']);
            }

            $employee = $this->repository->create($data);

            event(new EmployeeCreated($employee));

            return $employee;
        });
    }

    public function update(string $id, array $data): Employee
    {
        return DB::transaction(function () use ($id, $data) {
            $employee = $this->repository->findByIdOrFail($id);

            // Update user account details if provided
            $userData = [];
            if (isset($data['first_name']) || isset($data['last_name'])) {
                $firstName = $data['first_name'] ?? $employee->first_name;
                $lastName = $data['last_name'] ?? $employee->last_name;
                $userData['name'] = "{$firstName} {$lastName}";
            }

            if (isset($data['email'])) {
                $userData['email'] = $data['email'];
            }

            if (isset($data['password']) && ! empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            if (! empty($userData)) {
                $employee->user->update($userData);
            }

            // if profile photo was remove, set it to null
            if (isset($data['profile_photo_url']) && $data['profile_photo_url'] === '') {
                $data['profile_photo_url'] = null;
            }

            if (isset($data['profile_photo'])) {
                $path = $data['profile_photo']->store('employees', 'local');
                $data['profile_photo_url'] = $path;
                unset($data['profile_photo']);
            }

            $employee = $this->repository->update($id, $data);

            event(new EmployeeUpdated($employee));

            return $employee;
        });
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
