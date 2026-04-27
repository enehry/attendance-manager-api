<?php

declare(strict_types=1);

namespace App\Modules\HRIS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HRIS\Http\Requests\CreateEmployeeRequest;
use App\Modules\HRIS\Http\Requests\EmployeeFilterRequest;
use App\Modules\HRIS\Http\Requests\EmployeeUpdateRequest;
use App\Modules\HRIS\Http\Resources\EmployeeResource;
use App\Modules\HRIS\Services\EmployeeService;
use App\Shared\Traits\ApiResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Group('HRIS Module')]
class EmployeeController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly EmployeeService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeFilterRequest $request): JsonResponse
    {

        $filters = $request->validated();

        $employees = $this->service->paginateWithFilters($filters, 20);

        return $this->paginated(EmployeeResource::collection($employees), $employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $employee = $this->service->create($validated);

        return $this->created(new EmployeeResource($employee), 'Employee created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $employee = $this->service->getById($id);

        return $this->success(new EmployeeResource($employee), 'Employee retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeUpdateRequest $request, string $uuid): JsonResponse
    {
        $validated = $request->validated();
        $employee = $this->service->update($uuid, $validated);

        return $this->success(new EmployeeResource($employee), 'Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->success([], 'Employee deleted successfully');
    }

    /**
     * Permanently delete an employee.
     */
    public function forceDelete(string $id): JsonResponse
    {

        $this->service->forceDelete($id);

        return $this->success([], 'Employee force deleted successfully');
    }

    /**
     * Restore a soft-deleted employee.
     */
    public function restore(string $id): JsonResponse
    {
        $this->service->restore($id);

        return $this->success([], 'Employee restored successfully');
    }

    /**
     * Serve the employee's profile photo from private storage.
     */
    public function photo(?string $path): StreamedResponse
    {

        if (str_contains($path, '..')) {
            abort(403);
        }

        if (! $path || ! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->response($path, null, [
            'Cache-Control' => 'private, max-age=86400',
            'Content-Disposition' => 'inline',
        ]);
    }
}
