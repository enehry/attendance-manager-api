<?php

namespace App\Modules\HRIS\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'employee_number' => $this->employee_number,
            'full_name' => $this->full_name,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'employment_status' => $this->employment_status,
            'profile_photo_url' => $this->profile_photo_url ? route('employee-photo', $this->profile_photo_url) : null,
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'schedule' => $this->whenLoaded('schedule'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
