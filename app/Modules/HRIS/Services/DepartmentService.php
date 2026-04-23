<?php

namespace App\Modules\HRIS\Services;

use App\Modules\HRIS\Models\Department;
use App\Modules\HRIS\Repositories\DepartmentRepository;
use Illuminate\Database\Eloquent\Collection;

class DepartmentService
{
    public function __construct(
        private readonly DepartmentRepository $repository,
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->getAllActive();
    }

    public function getById(int $id): Department
    {
        return $this->repository->findByIdOrFail($id);
    }

    public function create(array $data): Department
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): Department
    {
        return $this->repository->update($id, $data);
    }

    // Lightweight list for dropdowns — just id + name
    public function getOptions(): array
    {
        return $this->repository->getAllActive()
            ->map(fn (Department $d) => [
                'value' => $d->id,
                'label' => $d->name,
            ])
            ->values()
            ->toArray();
    }
}
