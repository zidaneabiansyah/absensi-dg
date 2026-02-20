<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSemesterRequest;
use App\Http\Requests\UpdateSemesterRequest;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index(Request $request)
    {
        $query = Semester::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('academic_year', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $semesters = $query->latest()->paginate(10)->withQueryString();

        return view('semesters.index', compact('semesters'));
    }

    public function create()
    {
        return view('semesters.create');
    }

    public function store(StoreSemesterRequest $request)
    {
        $semester = Semester::create($request->validated());

        if ($request->status === 'active') {
            $semester->activate();
        }

        return redirect()
            ->route('semesters.index')
            ->with('success', 'Semester berhasil ditambahkan');
    }

    public function edit(Semester $semester)
    {
        return view('semesters.edit', compact('semester'));
    }

    public function update(UpdateSemesterRequest $request, Semester $semester)
    {
        $semester->update($request->validated());

        if ($request->status === 'active') {
            $semester->activate();
        }

        return redirect()
            ->route('semesters.index')
            ->with('success', 'Semester berhasil diperbarui');
    }

    public function destroy(Semester $semester)
    {
        if ($semester->status === 'active') {
            return redirect()
                ->route('semesters.index')
                ->with('error', 'Semester aktif tidak dapat dihapus');
        }

        $semester->delete();

        return redirect()
            ->route('semesters.index')
            ->with('success', 'Semester berhasil dihapus');
    }
}
