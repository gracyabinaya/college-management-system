<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('department')->get();
        return view('course.index', compact('courses'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('course.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required',
            'course_name' => 'required',
            'department_id' => 'required',
        ]);

        Course::create([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $departments = Department::all();

        return view('course.edit', compact('course', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $course->update([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Course Updated Successfully');
    }

    public function destroy($id)
    {
        Course::findOrFail($id)->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course Deleted Successfully');
    }
}