<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Models\Hrms\JobOffer;
use App\Models\Hrms\StaffRecruitmentOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffRecruitmentOrderController extends Controller
{
    public function index(): View
    {
        return view('admin.hrms.staffrecruitmentorders.index', $this->pageData());
    }

    public function store(Request $request): RedirectResponse
    {
        StaffRecruitmentOrder::create($this->validatedData($request));
        return to_route('admin.hrms.staffrecruitmentorders.index')->with('success', 'Data saved successfully.');
    }

    public function edit(int $staffRecruitmentOrder): View
    {
        return view('admin.hrms.staffrecruitmentorders.index', $this->pageData(StaffRecruitmentOrder::findOrFail($staffRecruitmentOrder)));
    }

    public function update(Request $request, int $staffRecruitmentOrder): RedirectResponse
    {
        StaffRecruitmentOrder::findOrFail($staffRecruitmentOrder)->update($this->validatedData($request));
        return to_route('admin.hrms.staffrecruitmentorders.index')->with('success', 'Data updated successfully.');
    }

    public function show(int $staffRecruitmentOrder): View
    {
        return view('admin.hrms.staffrecruitmentorders.index', $this->pageData(StaffRecruitmentOrder::findOrFail($staffRecruitmentOrder), true));
    }

    public function destroy(int $staffRecruitmentOrder): RedirectResponse
    {
        StaffRecruitmentOrder::findOrFail($staffRecruitmentOrder)->delete();
        return to_route('admin.hrms.staffrecruitmentorders.index')->with('success', 'Data deleted successfully.');
    }

    private function pageData(?StaffRecruitmentOrder $order = null, bool $viewMode = false): array
    {
        $offers = JobOffer::query()->leftJoin('job_applications as ja', 'job_offers.candidate_name', '=', 'ja.full_name')
            ->where('ja.status', 'Approved')->select('job_offers.candidate_name', 'job_offers.position', 'ja.cnic', 'ja.contact_numbers', 'ja.father_husband_name', 'ja.mailing_address')->orderBy('job_offers.candidate_name')->get()->unique('candidate_name')->values();
        if ($order && !$offers->contains(fn ($offer) => $offer->candidate_name === $order->employee_name)) $offers->push((object) ['candidate_name' => $order->employee_name, 'position' => $order->position, 'cnic' => $order->employee_cnic, 'contact_numbers' => $order->personal_phone]);
        return ['orders' => StaffRecruitmentOrder::query()->latest('id')->get(), 'order' => $order, 'offers' => $offers, 'viewMode' => $viewMode, 'today' => today()->toDateString()];
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'employee_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'order_date' => ['required', 'date'],
            'department' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'employee_cnic' => ['nullable', 'string', 'max:255'],
            'personal_phone' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
