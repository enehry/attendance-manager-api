<?php

declare(strict_types=1);

namespace App\Modules\HRIS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HRIS\Http\Requests\StoreDepartmentRequest;
use App\Modules\HRIS\Http\Requests\UpdateDepartmentRequest;
use App\Modules\HRIS\Http\Resources\DepartmentResource;
use App\Modules\HRIS\Services\DepartmentService;
use App\Shared\Traits\ApiResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;

#[Group('Departments')]
class DepartmentController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly DepartmentService $service,
    ) {}

    /**
     * List all departments.
     */
    public function index(): JsonResponse
    {
        $departments = $this->service->getAll();

        return $this->success(DepartmentResource::collection($departments));
    }

    /**
     * Get a single department.
     */
    public function show(int $id): JsonResponse
    {
        $department = $this->service->getById($id);

        return $this->success(new DepartmentResource($department));
    }

    /**
     * Create a new department.
     */
    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = $this->service->create($request->validated());

        return $this->created(new DepartmentResource($department));
    }

    /**
     * Update a department.
     */
    public function update(UpdateDepartmentRequest $request, int $id): JsonResponse
    {
        $department = $this->service->update($id, $request->validated());

        return $this->success(new DepartmentResource($department));
    }

    /**
     * Get lightweight department options for dropdowns.
     */
    public function options(): JsonResponse
    {
        $options = $this->service->getOptions();

        return $this->success($options);
    }
}
