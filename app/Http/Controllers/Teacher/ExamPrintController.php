<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Modules\ExamRegistration\Models\SrdExamRegistration;
use Illuminate\Http\Request;
use App\Support\Settings\Settings;

class ExamPrintController extends Controller
{
    public function index(Request $request)
    {
        $query = SrdExamRegistration::with(['examSession', 'examCenter'])->orderByDesc('id');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('citizen_id', 'like', "%{$q}%")
                    ->orWhere('reference_code', 'like', "%{$q}%");
            });
        }

        $registrations = $query->paginate(20)->withQueryString();

        return view('examregistration::teacher.print_index', [
            'registrations' => $registrations,
        ]);
    }

    public function show($id)
    {
        $registration = SrdExamRegistration::with(['examSession', 'examCenter'])->findOrFail($id);

        return view('examregistration::teacher.print_show', [
            'registration' => $registration,
            'schoolName' => Settings::get('general.school_name', 'สำนักเรียนวัดสารอด'),
        ]);
    }
}
