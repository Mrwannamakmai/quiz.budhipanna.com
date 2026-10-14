<?php

namespace App\Http\Controllers\Student;

use App\Certificate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::where('user_id', Auth::id())
            ->with(['examSet.subject.course'])
            ->latest('issued_at')
            ->paginate(15);

        return view('student.certificates.index', compact('certificates'));
    }

    public function show(Certificate $certificate)
    {
        abort_unless((int) $certificate->user_id === (int) Auth::id() || Auth::user()->isSuperAdmin(), 403);
        $certificate->load(['user', 'examSet.subject.course', 'issuedBy']);
        return view('student.certificates.show', compact('certificate'));
    }

    // Verify certificate by number (public)
    public function verify(string $certificateNo)
    {
        $certificate = Certificate::where('certificate_no', $certificateNo)
            ->with(['user', 'examSet.subject.course'])
            ->first();

        return view('certificates.verify', compact('certificate'));
    }
}
