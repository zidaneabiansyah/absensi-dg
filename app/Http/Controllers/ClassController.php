<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of classes
     */
    public function index(Request $request)
    {
        $query = ClassModel::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('homeroom_teacher', 'like', "%{$search}%")
                  ->orWhere('academic_year', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $classes = $query->withCount('students')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Get unique academic years for filter
        $academicYears = ClassModel::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        return view('classes.index', compact('classes', 'academicYears'));
    }

    /**
     * Show the form for creating a new class
     */
    public function create()
    {
        return view('classes.create');
    }

    /**
     * Store a newly created class
     */
    public function store(StoreClassRequest $request)
    {
        ClassModel::create($request->validated());

        return redirect()
            ->route('classes.index')
            ->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Display the specified class
     */
    public function show(ClassModel $class)
    {
        $class->load(['students' => function($query) {
            $query->orderBy('name');
        }]);

        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified class
     */
    public function edit(ClassModel $class)
    {
        return view('classes.edit', compact('class'));
    }

    /**
     * Update the specified class
     */
    public function update(UpdateClassRequest $request, ClassModel $class)
    {
        $class->update($request->validated());

        return redirect()
            ->route('classes.index')
            ->with('success', 'Kelas berhasil diperbarui');
    }

    /**
     * Remove the specified class
     */
    public function destroy(ClassModel $class)
    {
        // Check if class has students
        if ($class->students()->count() > 0) {
            return redirect()
                ->route('classes.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa');
        }

        $class->delete();

        return redirect()
            ->route('classes.index')
            ->with('success', 'Kelas berhasil dihapus');
    }
}
