<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $students = Student::query()
            ->when($search, function ($query, $search) {
                $query->where('student_name', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%")
                      ->orWhere('contact_number', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12);

        return view('students.index', compact('students'));
    }

    public function create()
{
    $courses = Course::all();

    return view('students.create', compact('courses'));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:100',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female,Other',
            'father_name' => 'required|string|max:255',
            'last_class_studied' => 'required|string|max:100',
            'previous_year_grade' => 'required|string|max:50',
            'last_school_studied' => 'required|string|max:255',
            'contact_number' => 'required|string|digits:10',
            'alternate_contact_number' => 'nullable|string|digits:10',
            'aadhaar_number' => 'required|string|digits:12',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'course_id' => 'required|exists:courses,id',
        ]);

        $data['student_id'] = 'STU-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . Str::slug($request->student_name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/students'), $filename);
            $data['photo'] = $filename;
        }

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
{
    $courses = Course::all();

    return view('students.edit', compact('student', 'courses'));
}

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'student_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:100',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female,Other',
            'father_name' => 'required|string|max:255',
            'last_class_studied' => 'required|string|max:100',
            'previous_year_grade' => 'required|string|max:50',
            'last_school_studied' => 'required|string|max:255',
            'contact_number' => 'required|string|digits:10',
            'alternate_contact_number' => 'nullable|string|digits:10',
            'aadhaar_number' => 'required|string|digits:12',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo && file_exists(public_path('uploads/students/' . $student->photo))) {
                @unlink(public_path('uploads/students/' . $student->photo));
            }
            $file = $request->file('photo');
            $filename = time() . '_' . Str::slug($request->student_name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/students'), $filename);
            $data['photo'] = $filename;
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Student record updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->photo && file_exists(public_path('uploads/students/' . $student->photo))) {
            @unlink(public_path('uploads/students/' . $student->photo));
        }

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student record deleted successfully.');
    }

    public function print(Student $student)
    {
        return view('students.print', compact('student'));
    }
}
