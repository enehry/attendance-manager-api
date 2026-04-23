<?php

namespace App\Modules\HRIS\Http\Requests;

use App\Shared\Enums\EmployeeStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'employee_status' => [
                'nullable',
                Rule::in(EmployeeStatus::class),
            ],
            'sort_by' => 'nullable|in:id,employee_number,full_name,created_at,updated_at',
            'sort_order' => 'nullable|in:asc,desc',
        ];
    }
}
