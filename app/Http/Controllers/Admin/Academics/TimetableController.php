<?php

namespace App\Http\Controllers\Admin\Academics;

use Dompdf\Dompdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TimetableController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        $branchId = $request->integer('brc_id') ?: app(\App\Services\BranchContext::class)->id();
        $branches = DB::table('branch')->orderBy('name')->get(['id', 'name']);
        $classes = DB::table('classes')->orderBy('id')->get(['id', 'class']);
        $sections = DB::table('sections')->orderBy('section')->get(['id', 'section']);
        $subjects = DB::table('subjects')->orderBy('name')->get(['id', 'name', 'code']);
        $teachers = DB::table('staff')->where('is_active', 1)->orderBy('name')->get(['id', 'name', 'surname']);
        $slots = DB::table('timeallocation')
            ->where('brc_id', $branchId)
            ->orderBy('id')
            ->get(['id', 'slot', 'start_time', 'end_time', 'f_start_time', 'f_end_time']);
        $records = $this->records($request, $branchId);

        return view('admin.academics.timetable.index', compact('branches', 'classes', 'sections', 'subjects', 'teachers', 'slots', 'records', 'branchId'));
    }

    public function masterTimetable(Request $request): View
    {
        $branchId = $request->integer('brc_id') ?: app(\App\Services\BranchContext::class)->id();
        $branches = DB::table('branch')->orderBy('name')->get(['id', 'name']);
        $records = collect();

        if ($request->boolean('searched')) {
            $records = DB::table('subject_timetable as timetable')
                ->leftJoin('branch', 'branch.id', '=', 'timetable.brc_id')
                ->leftJoin('classes', 'classes.id', '=', 'timetable.class_id')
                ->leftJoin('sections', 'sections.id', '=', 'timetable.section_id')
                ->leftJoin('subjects', 'subjects.id', '=', 'timetable.subject_id')
                ->leftJoin('staff', 'staff.id', '=', 'timetable.staff_id')
                ->leftJoin('timeallocation', 'timeallocation.id', '=', 'timetable.slot_id')
                ->when($branchId, fn ($query) => $query->where('timetable.brc_id', $branchId))
                ->orderByRaw("FIELD(timetable.day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
                ->orderBy('timeallocation.start_time')
                ->get(['branch.name as branch_name', 'classes.class as class_name', 'sections.section as section_name', 'subjects.name as subject_name', DB::raw("TRIM(CONCAT(staff.name, ' ', COALESCE(staff.surname, ''))) as teacher_name"), 'timetable.day', 'timeallocation.slot as slot_name', 'timeallocation.start_time', 'timeallocation.end_time']);
        }

        return view('admin.academics.timetable.master-timetable', compact('branches', 'branchId', 'records'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'brc_id' => ['required', 'exists:branch,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'slot_id' => ['required', 'exists:timeallocation,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'staff_id' => ['required', 'exists:staff,id'],
            'days' => ['required', 'array', 'min:1'],
            'days.*' => ['in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday'],
        ]);

        foreach ($data['days'] as $day) {
            DB::table('subject_timetable')->insert([
                'brc_id' => $data['brc_id'], 'day' => $day, 'class_id' => $data['class_id'],
                'section_id' => $data['section_id'], 'subject_id' => $data['subject_id'],
                'staff_id' => $data['staff_id'], 'slot_id' => $data['slot_id'],
                'session_id' => app(\App\Services\AcademicSessionContext::class)->id(),
                'created_at' => now(),
            ]);
        }

        return redirect()->route('admin.academics.timetables.index', ['brc_id' => $data['brc_id']])->with(['toast_type' => 'success', 'toast_message' => 'Timetable saved successfully.']);
    }

    public function slots(int $branchId): JsonResponse
    {
        return response()->json(DB::table('timeallocation')->where('brc_id', $branchId)->orderBy('id')->get(['id', 'slot', 'start_time', 'end_time']));
    }

    public function destroy(int $timetable): RedirectResponse
    {
        DB::table('subject_timetable')->where('id', $timetable)->delete();
        return back()->with(['toast_type' => 'success', 'toast_message' => 'Timetable deleted successfully.']);
    }

    public function print(Request $request): View
    {
        return view('admin.academics.timetable.print', ['records' => $this->records($request, $request->integer('brc_id') ?: app(\App\Services\BranchContext::class)->id())]);
    }

    public function pdf(Request $request)
    {
        $html = view('admin.academics.timetable.print', ['records' => $this->records($request, $request->integer('brc_id') ?: app(\App\Services\BranchContext::class)->id())])->render();
        $printCss = file_get_contents(public_path('assets/css/timetable-print.css'));
        $html = str_replace('</head>', '<style>'.$printCss.'</style></head>', $html);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        return response($dompdf->output(), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="timetable.pdf"']);
    }

    private function records(Request $request, ?int $branchId)
    {
        return DB::table('subject_timetable as timetable')
            ->leftJoin('branch', 'branch.id', '=', 'timetable.brc_id')
            ->leftJoin('classes', 'classes.id', '=', 'timetable.class_id')
            ->leftJoin('sections', 'sections.id', '=', 'timetable.section_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'timetable.subject_id')
            ->leftJoin('staff', 'staff.id', '=', 'timetable.staff_id')
            ->leftJoin('timeallocation', 'timeallocation.id', '=', 'timetable.slot_id')
            ->leftJoin('period', 'period.id', '=', 'timetable.slot_id')
            ->when($branchId, fn ($query) => $query->where('timetable.brc_id', $branchId))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search): void {
                    $query->where('branch.name', 'like', "%{$search}%")->orWhere('classes.class', 'like', "%{$search}%")
                        ->orWhere('subjects.name', 'like', "%{$search}%")->orWhere('staff.name', 'like', "%{$search}%")
                        ->orWhere('timetable.day', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('timetable.id')
            ->get(['timetable.*', 'branch.name as branch_name', 'classes.class as class_name', 'sections.section as section_name', 'subjects.name as subject_name', 'subjects.code as subject_code', DB::raw("TRIM(CONCAT(staff.name, ' ', COALESCE(staff.surname, ''))) as teacher_name"), 'timeallocation.slot as slot_name', 'timeallocation.start_time', 'timeallocation.end_time', 'period.name as period_name']);
    }
}
