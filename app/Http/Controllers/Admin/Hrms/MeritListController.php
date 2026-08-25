<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MeritListController extends Controller
{
    public function index(): View
    {
        $candidates = DB::table('job_applications as ja')
            ->join('interview_ratings as ir', function ($join): void {
                $join->on('ja.full_name', '=', 'ir.candidate_name')->on('ja.position_applied', '=', 'ir.position_applied');
            })
            ->where('ja.status', 'Approved')->where('ir.final_decision', 'recommended')->whereNotNull('ja.written_test_marks')
            ->select('ja.id', 'ja.full_name', 'ja.position_applied',
                DB::raw('COALESCE(ja.written_test_marks, 0) as written_test_marks'),
                DB::raw('COALESCE(ja.written_test_total, 100) as written_test_total'),
                DB::raw('COALESCE(ir.total_points, 0) as interview_marks'),
                DB::raw('(COALESCE(ja.written_test_marks, 0) + COALESCE(ir.total_points, 0)) as total_marks'))
            ->orderByDesc('total_marks')->get()->values();
        return view('admin.hrms.meritlist.index', compact('candidates'));
    }
}
