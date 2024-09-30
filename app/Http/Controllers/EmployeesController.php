<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index() {
        $list = Employee::where('employment_status', 'ACTIVE')->get();

        return view('employees.index', [
            'list' => $list,
        ]);
    }

    public function addEmployee() {
        return $this->newOrEdit(new Employee(), 'NEW');
    }

    public function storeEmployee(Request $r) {

    }

    public function editEmployee($id) {
        $employee = Employee::findOrFail($id);

        return $this->newOrEdit($employee, 'EDIT');
    }

    public function newOrEdit(Employee $record, $mode) {
        return view('employees.new_or_edit', [
            'd' => $record,
            'mode' => $mode,
        ]);
    }

    public function updateEmployee($id, Request $r) {
        
    }
}
