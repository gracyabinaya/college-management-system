<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
        ]);

        Department::create([
            'department_name' => $request->department_name,
        ]);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully');
    }
    public function edit(Department $department)
{
    return view('departments.edit', compact('department'));
}

public function update(Request $request, Department $department)
{
    $request->validate([
        'department_name' => 'required',
    ]);

    $department->update([
        'department_name' => $request->department_name,
    ]);

    return redirect()->route('departments.index')
        ->with('success', 'Department updated successfully');
}
public function destroy(Department $department)
{
    // safety check (important)
    if ($department->courses()->count() > 0) {
        return back()->with('error', 'Cannot delete department linked with courses');
    }

    $department->delete();

    return back()->with('success', 'Department deleted successfully');
}
}
