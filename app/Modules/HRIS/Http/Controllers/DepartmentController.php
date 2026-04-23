<?php

declare(strict_types=1);

namespace App\Modules\HRIS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HRIS\Http\Requests\StoreDepartmentRequest;
use App\Modules\HRIS\Http\Requests\UpdateDepartmentRequest;
use App\Modules\HRIS\Http\Resources\DepartmentResource;
use App\Modules\HRIS\Services\DepartmentService;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly DepartmentService $service,
    ) {}

    public function index(): JsonResponse
    {
        $departments = $this->service->getAll();

        return $this->success(DepartmentResource::collection($departments));
    }

    public function show(int $id): JsonResponse
    {
        $department = $this->service->getById($id);

        return $this->success(new DepartmentResource($department));
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = $this->service->create($request->validated());

        return $this->created(new DepartmentResource($department));
    }

    public function update(UpdateDepartmentRequest $request, int $id): JsonResponse
    {
        $department = $this->service->update($id, $request->validated());

        return $this->success(new DepartmentResource($department));
    }

    // Separate lightweight endpoint for dropdowns
    // Returns only id + name — nothing else needed
    public function options(): JsonResponse
    {
        $options = $this->service->getOptions();

        return $this->success($options);
    }
}
