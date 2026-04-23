<?php

namespace App\Modules\HRIS\Http\Requests;

use App\Shared\Enums\EmploymentStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateEmployeeRequest extends FormRequest
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
            // 'employee_number' => 'required|string|size:8|unique:employees,employee_number',
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'employment_status' => [
                'required',
                Rule::enum(EmploymentStatus::class),
            ],
            'schedule_id' => 'required|exists:schedules,id',
            'department_id' => 'required|exists:departments,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
