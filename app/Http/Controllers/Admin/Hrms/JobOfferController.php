<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Models\Hrms\InterviewRating;
use App\Models\Hrms\JobApplication;
use App\Models\Hrms\JobOffer;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOfferController extends Controller
{
    private const STATUSES = ['pending', 'accepted', 'rejected'];

    public function index(): View
    {
        return view('admin.hrms.joboffers.index', $this->pageData());
    }

    public function store(Request $request): RedirectResponse
    {
        $offer = JobOffer::create($this->validatedData($request) + ['status' => 'pending']);
        $offer->update(['reference_no' => 'JOB/REF/' . now()->format('Y') . '/' . str_pad((string) $offer->id, 3, '0', STR_PAD_LEFT)]);
        return to_route('admin.hrms.joboffers.index')->with('success', 'Data saved successfully.');
    }

    public function edit(int $jobOffer): View
    {
        return view('admin.hrms.joboffers.index', $this->pageData(JobOffer::findOrFail($jobOffer)));
    }

    public function update(Request $request, int $jobOffer): RedirectResponse
    {
        $status = $request->validate(['status' => ['nullable', 'in:' . implode(',', self::STATUSES)]])['status'] ?? 'pending';
        JobOffer::findOrFail($jobOffer)->update($this->validatedData($request) + ['status' => $status]);
        return to_route('admin.hrms.joboffers.index')->with('success', 'Data updated successfully.');
    }

    public function show(int $jobOffer): View
    {
        return view('admin.hrms.joboffers.index', $this->pageData(JobOffer::findOrFail($jobOffer), true));
    }

    public function destroy(int $jobOffer): RedirectResponse
    {
        JobOffer::findOrFail($jobOffer)->delete();
        return to_route('admin.hrms.joboffers.index')->with('success', 'Data deleted successfully.');
    }

    public function print(int $jobOffer): View
    {
        $offer = JobOffer::query()->leftJoin('job_applications as ja', 'job_offers.candidate_name', '=', 'ja.full_name')
            ->where('job_offers.id', $jobOffer)->select('job_offers.*', 'ja.contact_numbers', 'ja.cnic', 'ja.mailing_address', 'ja.written_test_marks', 'ja.status as application_status', 'ja.created_at as application_date')->firstOrFail();
        $offer->application_date = $offer->application_date ? Carbon::parse($offer->application_date) : null;
        return view('admin.hrms.joboffers.print', ['offer' => $offer]);
    }

    private function pageData(?JobOffer $offer = null, bool $viewMode = false): array
    {
        $candidates = JobApplication::query()->join('interview_ratings as ir', function ($join): void {
            $join->on('job_applications.full_name', '=', 'ir.candidate_name')->on('job_applications.position_applied', '=', 'ir.position_applied');
        })->where('job_applications.status', 'Approved')->where('ir.final_decision', 'recommended')->select('job_applications.*')->orderBy('full_name')->get()->unique(fn ($candidate) => $candidate->full_name . '|' . $candidate->position_applied)->values();
        if ($offer && !$candidates->contains(fn ($candidate) => $candidate->full_name === $offer->candidate_name)) $candidates->push((object) ['full_name' => $offer->candidate_name, 'position_applied' => $offer->position]);
        return ['offers' => JobOffer::query()->latest('created_at')->get(), 'offer' => $offer, 'candidates' => $candidates, 'viewMode' => $viewMode, 'statuses' => self::STATUSES, 'today' => today()->toDateString()];
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'candidate_name' => ['required', 'string', 'max:255'], 'position' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'], 'offer_date' => ['required', 'date'], 'joining_date' => ['required', 'date'],
            'salary' => ['required', 'numeric', 'min:0'],
        ]);
        $data['basic_salary'] = $data['salary'];
        unset($data['salary']);
        return $data;
    }
}
