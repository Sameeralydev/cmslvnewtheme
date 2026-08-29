<?php

namespace App\Http\Controllers\Admin\Academics;

use Dompdf\Dompdf;
use App\Models\Academics\TimeAllocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TimeAllocationController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        $branchId = $request->integer('brc_id') ?: app(\App\Services\BranchContext::class)->id();
        $branches = DB::table('branch')->orderBy('name')->get(['id', 'name']);
        $classes = DB::table('classes')->orderBy('id')->get(['id', 'class']);
        $periods = DB::table('period')->orderBy('id')->get(['id', 'name']);
        $slotTypes = ['regular' => 'Regular Period', 'assembly' => 'Morning Assembly', 'break' => 'Recess/Break', 'zero' => 'Zero Period'];
        $wingShifts = ['junior' => 'Junior Wing', 'senior' => 'Senior Wing', 'morning' => 'Morning Shift', 'afternoon' => 'Afternoon Shift'];
        $records = TimeAllocation::query()->leftJoin('branch', 'branch.id', '=', 'timeallocation.brc_id')->leftJoin('classes', 'classes.id', '=', 'timeallocation.class_id')->leftJoin('period', 'period.id', '=', 'timeallocation.period_id')->when($branchId, fn ($q) => $q->where('timeallocation.brc_id', $branchId))->orderByDesc('timeallocation.id')->get(['timeallocation.*', 'branch.name as branch_name', 'classes.class as class_name', 'period.name as period_name']);
        return view('admin.academics.timetable.time-allocation', compact('branches', 'classes', 'periods', 'records', 'branchId', 'slotTypes', 'wingShifts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['brc_id' => ['required', 'exists:branch,id'], 'period_id' => ['nullable', 'exists:period,id'], 'slot' => ['required', 'string', 'max:100'], 'slot_type' => ['required', 'in:regular,assembly,break,zero'], 'wing_shift' => ['nullable', 'in:junior,senior,morning,afternoon'], 'applies_days' => ['nullable', 'array'], 'applies_days.*' => ['in:Monday,Tuesday,Wednesday,Thursday'], 'special_schedule' => ['nullable', 'in:friday,half_day'], 'class_id' => ['required', 'exists:classes,id'], 'time_from' => ['required', 'date_format:H:i'], 'time_to' => ['required', 'date_format:H:i'], 'f_time_from' => ['required', 'date_format:H:i'], 'f_time_to' => ['required', 'date_format:H:i']]);
        TimeAllocation::query()->create(['brc_id' => $data['brc_id'], 'period_id' => $data['period_id'] ?? null, 'slot' => $data['slot'], 'slot_type' => $data['slot_type'], 'wing_shift' => $data['wing_shift'] ?? null, 'applies_days' => !empty($data['applies_days']) ? implode(', ', $data['applies_days']) : null, 'special_schedule' => $data['special_schedule'] ?? null, 'class_id' => $data['class_id'] ?? null, 'start_time' => $data['time_from'], 'end_time' => $data['time_to'], 'f_start_time' => $data['f_time_from'], 'f_end_time' => $data['f_time_to'], 'is_active' => 1]);
        return redirect()->route('admin.academics.time-allocation.index', ['brc_id' => $data['brc_id']])->with(['toast_type' => 'success', 'toast_message' => 'Time Allocation saved successfully.']);
    }

    public function destroy(int $timeAllocation): RedirectResponse
    {
        TimeAllocation::query()->findOrFail($timeAllocation)->delete();
        return back()->with(['toast_type' => 'success', 'toast_message' => 'Time Allocation deleted successfully.']);
    }

    public function print(Request $request): View
    {
        return view('admin.academics.timetable.time-allocation-print', ['records' => $this->records($request), 'slotTypes' => $this->slotTypes(), 'wingShifts' => $this->wingShifts()]);
    }

    public function pdf(Request $request)
    {
        $html = view('admin.academics.timetable.time-allocation-print', ['records' => $this->records($request), 'slotTypes' => $this->slotTypes(), 'wingShifts' => $this->wingShifts()])->render();
        $dompdf = new Dompdf(); $dompdf->loadHtml($html, 'UTF-8'); $dompdf->setPaper('A4', 'landscape'); $dompdf->render();
        return response($dompdf->output(), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="time-allocation.pdf"']);
    }

    private function records(Request $request)
    {
        $branchId = $request->integer('brc_id') ?: app(\App\Services\BranchContext::class)->id();
        return TimeAllocation::query()->leftJoin('branch', 'branch.id', '=', 'timeallocation.brc_id')->leftJoin('classes', 'classes.id', '=', 'timeallocation.class_id')->leftJoin('period', 'period.id', '=', 'timeallocation.period_id')->when($branchId, fn ($q) => $q->where('timeallocation.brc_id', $branchId))->orderByDesc('timeallocation.id')->get(['timeallocation.*', 'branch.name as branch_name', 'classes.class as class_name', 'period.name as period_name']);
    }

    private function slotTypes(): array { return ['regular' => 'Regular Period', 'assembly' => 'Morning Assembly', 'break' => 'Recess/Break', 'zero' => 'Zero Period']; }
    private function wingShifts(): array { return ['junior' => 'Junior Wing', 'senior' => 'Senior Wing', 'morning' => 'Morning Shift', 'afternoon' => 'Afternoon Shift']; }
}
