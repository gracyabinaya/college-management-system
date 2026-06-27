<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;


class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['student', 'course.department'])->get();
        return view('enrollment.index', compact('enrollments'));
    }

   public function create()
{
    $students = Student::all();
    $courses = Course::with('department')->get();

    return view('enrollment.create', compact('students', 'courses'));
}
    public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required',
        'course_id' => 'required',
    ]);

    Enrollment::create([
        'student_id' => $request->student_id,
        'course_id' => $request->course_id,
    ]);

    return redirect()->route('enrollments.index')
        ->with('success', 'Student Enrolled Successfully');
}
public function destroy($id)
{
    $enrollment = Enrollment::findOrFail($id);
    $enrollment->delete();

    return redirect()->route('enrollments.index')
        ->with('success', 'Enrollment deleted successfully');
}}