<?php

namespace App\Modules\HRIS\Http\Requests;

use App\Shared\Enums\EmploymentStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(
                    \App\Modules\HRIS\Models\Employee::where('uuid', $this->route('employee'))->first()?->user_id
                ),
            ],
            'password' => 'nullable|string|min:8',
        ];
    }
}
