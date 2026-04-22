<?php

declare(strict_types=1);

namespace App\Shared\Abstracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * All module repositories extend this.
 * Provides consistent find/paginate/create/update/delete
 * so every module doesn't re-implement boilerplate.
 */
abstract class BaseRepository
{
    abstract protected function model(): string;

    protected function newQuery()
    {
        return app($this->model())->newQuery();
    }

    public function findById(string $id): ?Model
    {
        return $this->newQuery()->where('uuid', $id)->first();
    }

    public function findByIdOrFail(string $id): Model
    {
        return $this->newQuery()->where('uuid', $id)->firstOrFail();
    }

    public function all(): Collection
    {
        return $this->newQuery()->get();
    }

    public function paginate(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        return $this->applyFilters($this->newQuery(), $filters)->paginate($perPage);
    }

    public function create(array $data): Model
    {
        return $this->newQuery()->create($data);
    }

    public function update(string $id, array $data): Model
    {
        $record = $this->findByIdOrFail($id);
        $record->update($data);

        return $record->fresh();
    }

    public function delete(string $id): bool
    {
        return $this->findByIdOrFail($id)->delete();
    }

    /**
     * Override in child repositories to apply module-specific filters.
     */
    protected function applyFilters($query, array $filters)
    {
        return $query;
    }
}
