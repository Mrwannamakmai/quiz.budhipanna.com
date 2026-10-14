<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Modules\ExamRegistration\Http\Requests\FollowUpRequest;
use App\Modules\ExamRegistration\Models\SrdExamFollowUp;
use App\Modules\ExamRegistration\Models\SrdExamRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamFollowUpController extends Controller
{
    public function index(Request $request)
    {
        $query = SrdExamRegistration::where('status', 'APPROVED')
            ->where(function ($q) {
                $q->whereNull('follow_up_status')
                    ->orWhereIn('follow_up_status', ['FOLLOW_PENDING', 'FOLLOW_IN_PROGRESS']);
            });

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($w) use ($search) {
                $w->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('line_id', 'like', "%{$search}%")
                    ->orWhere('citizen_id', 'like', "%{$search}%")
                    ->orWhere('reference_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('follow_up_status')) {
            $query->where('follow_up_status', $request->follow_up_status);
        }

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'class':
                $query->orderBy('target_level')->orderBy('target_range')->orderBy('first_name');
                break;
            case 'name':
                $query->orderBy('first_name')->orderBy('last_name');
                break;
            default:
                $query->orderByDesc('id');
                break;
        }

        $registrations = $query->paginate(20)->withQueryString();

        return view('examregistration::teacher.followup_index', [
            'registrations' => $registrations,
            'followUpStatuses' => SrdExamRegistration::FOLLOW_UP_STATUSES,
            'currentSort' => $sort,
        ]);
    }

    public function edit($registrationId)
    {
        $registration = SrdExamRegistration::with('followUps.followedBy')->findOrFail($registrationId);

        return view('examregistration::teacher.followup_edit', [
            'registration' => $registration,
            'followUpStatuses' => SrdExamRegistration::FOLLOW_UP_STATUSES,
        ]);
    }

    public function update(FollowUpRequest $request, $registrationId)
    {
        $registration = SrdExamRegistration::findOrFail($registrationId);

        $data = $request->validated();

        SrdExamFollowUp::create([
            'exam_registration_id' => $registration->id,
            'followed_at' => $data['followed_at'] ?? now(),
            'channel' => $data['channel'] ?? null,
            'result' => $data['result'] ?? null,
            'notes' => $data['notes'] ?? null,
            'followed_by' => Auth::id(),
        ]);

        if (!empty($data['follow_up_status'])) {
            $registration->update(['follow_up_status' => $data['follow_up_status']]);
        }

        return redirect()->route('teacher.exam_registration.followup.edit', $registration->id)
            ->with('success', 'บันทึกการติดตามแล้ว');
    }
}
