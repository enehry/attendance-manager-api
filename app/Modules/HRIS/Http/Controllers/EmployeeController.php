<?php

declare(strict_types=1);

namespace App\Modules\HRIS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HRIS\Http\Requests\CreateEmployeeRequest;
use App\Modules\HRIS\Http\Requests\EmployeeUpdateRequest;
use App\Modules\HRIS\Services\EmployeeService;
use App\Shared\Traits\ApiResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[Group('HRIS Module')]
class EmployeeController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly EmployeeService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {

        // $validated = $request->validated();
        $employees = $this->service->paginateWithFilters([], 20);

        return $this->success($employees, 'Employee retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $employee = $this->service->create($validated);

        return $this->created($employee, 'Employee created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $employee = $this->service->getEmployeeById($id);

        return $this->success($employee, 'Employee retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeUpdateRequest $request, string $uuid): JsonResponse
    {
        $validated = $request->validated();
        $employee = $this->service->update($uuid, $validated);

        return $this->success($employee, 'Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->success([], 'Employee deleted successfully');
    }

    public function forceDelete(string $id): JsonResponse
    {

        $this->service->forceDelete($id);

        return $this->success([], 'Employee force deleted successfully');
    }

    public function restore(string $id): JsonResponse
    {
        $this->service->restore($id);

        return $this->success([], 'Employee restored successfully');
    }
}
