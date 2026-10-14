<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Modules\ExamRegistration\Models\SrdExamRegistration;
use Illuminate\Support\Facades\DB;

class ExamReportController extends Controller
{
    public function index()
    {
        return view('examregistration::teacher.reports', [
            'byTargetLevel' => $this->countBy('target_level'),
            'byTargetRange' => $this->countBy('target_range'),
            'byStatus'      => $this->countByStatus(),
            'byEducationLevel' => $this->countBy('education_level'),
            'byProvince'    => $this->countBy('province'),
            'byReferralSource' => $this->countByReferralSource(),
        ]);
    }

    protected function countBy($column)
    {
        $rows = SrdExamRegistration::selectRaw("{$column} as label, count(*) as total")
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->groupBy($column)
            ->orderByDesc('total')
            ->get();

        $total = $rows->sum('total');

        return ['rows' => $rows, 'total' => $total];
    }

    protected function countByStatus()
    {
        $rows = SrdExamRegistration::selectRaw('status as label, count(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {
                $row->label = SrdExamRegistration::STATUSES[$row->label] ?? $row->label;
                return $row;
            });

        $total = $rows->sum('total');

        return ['rows' => $rows, 'total' => $total];
    }

    /** referral_sources เก็บเป็น string คั่นด้วย comma ต้องแยกออกมานับทีละค่าในฝั่ง PHP */
    protected function countByReferralSource()
    {
        $values = SrdExamRegistration::whereNotNull('referral_sources')
            ->where('referral_sources', '!=', '')
            ->pluck('referral_sources');

        $counts = [];
        foreach ($values as $value) {
            foreach (array_filter(array_map('trim', explode(',', $value))) as $source) {
                $counts[$source] = ($counts[$source] ?? 0) + 1;
            }
        }

        arsort($counts);

        $rows = collect($counts)->map(function ($total, $label) {
            return (object) ['label' => $label, 'total' => $total];
        })->values();

        return ['rows' => $rows, 'total' => array_sum($counts)];
    }
}
