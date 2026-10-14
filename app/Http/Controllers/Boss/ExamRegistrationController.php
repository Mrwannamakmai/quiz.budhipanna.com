<?php

namespace App\Http\Controllers\Boss;

use App\ExamRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamRegistration::with('examCenter')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhere('reference_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $registrations = $query->paginate(30);

        return view('boss.registrations.index', compact('registrations'));
    }

    public function show(ExamRegistration $registration)
    {
        return view('boss.registrations.show', compact('registration'));
    }

    public function edit(ExamRegistration $registration)
    {
        return view('boss.registrations.edit', compact('registration'));
    }

    public function update(Request $request, ExamRegistration $registration)
    {
        $data = $request->validate([
            'title' => 'required|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:13',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:registered,checked,eligible,absent',
            'remarks' => 'nullable|string',
            'exam_tier' => 'required|in:tree,tho,ek',
            'education_level' => 'nullable|string|max:255',
            'office_name' => 'nullable|string|max:255',
        ]);

        $registration->update($data);

        return redirect()->route('boss.registrations.index')->with('success', 'ปรับปรุงข้อมูลผู้สมัครเรียบร้อยแล้ว');
    }
}
