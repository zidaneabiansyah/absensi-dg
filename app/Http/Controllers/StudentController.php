<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        $query = Student::with('class');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->latest()
            ->paginate(15)
            ->withQueryString();

        // Get all classes for filter
        $classes = ClassModel::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('students.index', compact('students', 'classes'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        $classes = ClassModel::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('students.create', compact('classes'));
    }

    /**
     * Store a newly created student
     */
    public function store(StoreStudentRequest $request)
    {
        Student::create($request->validated());

        return redirect()
            ->route('students.index')
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $student->load(['class', 'attendances' => function($query) {
            $query->latest('date')->limit(30);
        }]);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student)
    {
        $classes = ClassModel::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified student
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        return redirect()
            ->route('students.index')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Siswa berhasil dihapus');
    }

    /**
     * Export students to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Student::with('class')->where('status', 'active');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->orderBy('name')->get();
        
        return Excel::download(new StudentsExport($students), 'data-siswa-' . now()->format('Y-m-d') . '.xlsx');
    }
}
