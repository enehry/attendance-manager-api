<?php

namespace App\Shared\Contracts;

use App\Modules\HRIS\DTOs\EmployeeData;
use Spatie\LaravelData\DataCollection;

/**
 * Contract that HRIS module implements.
 * All other modules (Payroll, DTRS, IPCR) depend on this interface,
 * never on HRIS models directly. This is the boundary enforcement.
 */
interface EmployeeDataContract
{
    public function getActiveEmployees(): DataCollection;

    public function getEmployeeById(string $id): EmployeeData;

    public function getEmployeeByNumber(string $employeeNumber): EmployeeData;

    public function employeeExists(string $id): bool;
}
