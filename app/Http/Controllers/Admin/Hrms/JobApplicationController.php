<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Models\Hrms\JobAdvertisement;
use App\Models\Hrms\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    private const RELIGIONS = ['Islam', 'Christianity', 'Hinduism', 'Other'];
    private const GENDERS = ['Male', 'Female'];
    private const MARITAL_STATUSES = ['Single', 'Married', 'Divorced', 'Widowed'];
    private const STATUSES = ['Pending', 'Approved', 'Rejected'];
    private const DEGREE_LEVELS = ['SSC', 'HSSC/Intermediate', 'Diploma', 'Bachelor/Graduation', 'Masters', 'M.Phil', 'Ph.D', 'B.Ed', 'M.Ed', 'Certificate Course', 'Other'];

    public function index(): View
    {
        return view('admin.hrms.jobapplications.index', $this->pageData());
    }

    public function store(Request $request): RedirectResponse
    {
        JobApplication::create($this->validatedData($request) + ['status' => 'Pending']);
        return to_route('admin.hrms.jobapplications.index')->with('success', 'Job application created successfully.');
    }

    public function edit(int $jobApplication): View
    {
        return view('admin.hrms.jobapplications.index', $this->pageData(JobApplication::findOrFail($jobApplication)));
    }

    public function update(Request $request, int $jobApplication): RedirectResponse
    {
        $application = JobApplication::findOrFail($jobApplication);
        $data = $this->validatedData($request, $application);
        $application->update($data);
        return to_route('admin.hrms.jobapplications.index')->with('success', 'Job application updated successfully.');
    }

    public function show(int $jobApplication): View
    {
        return view('admin.hrms.jobapplications.index', $this->pageData(JobApplication::findOrFail($jobApplication), true));
    }

    public function destroy(int $jobApplication): RedirectResponse
    {
        JobApplication::findOrFail($jobApplication)->delete();
        return to_route('admin.hrms.jobapplications.index')->with('success', 'Job application deleted successfully.');
    }

    public function jobDetails(Request $request): JsonResponse
    {
        $salary = JobAdvertisement::query()->where('job_title', $request->string('position')->toString())->value('salary');
        return response()->json(['salary' => $salary ?? '']);
    }

    public function updateStatus(Request $request, int $jobApplication): JsonResponse
    {
        $data = $request->validate(['status' => ['required', 'in:' . implode(',', self::STATUSES)]]);
        JobApplication::findOrFail($jobApplication)->update($data);
        return response()->json(['success' => true]);
    }

    public function print(int $jobApplication): View
    {
        return view('admin.hrms.jobapplications.print', ['application' => JobApplication::findOrFail($jobApplication)]);
    }

    private function pageData(?JobApplication $application = null, bool $viewMode = false): array
    {
        return [
            'applications' => JobApplication::query()->latest('created_at')->get(),
            'application' => $application,
            'viewMode' => $viewMode,
            'jobTitles' => JobAdvertisement::query()->where('status', 'active')->whereDate('deadline', '>=', today())->orderBy('job_title')->pluck('job_title')->unique()->values(),
            'religions' => self::RELIGIONS,
            'genders' => self::GENDERS,
            'maritalStatuses' => self::MARITAL_STATUSES,
            'statuses' => self::STATUSES,
            'degreeLevels' => self::DEGREE_LEVELS,
        ];
    }

    private function validatedData(Request $request, ?JobApplication $application = null): array
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'father_husband_name' => ['required', 'string', 'max:255'],
            'cnic' => ['required', 'string', 'max:15', 'regex:/^\d{5}-\d{7}-\d$/'],
            'position_applied' => ['required', 'string', 'max:255'],
            'min_salary_acceptable' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'religion' => ['required', 'in:' . implode(',', self::RELIGIONS)],
            'gender' => ['required', 'in:' . implode(',', self::GENDERS)],
            'height_ft' => ['nullable', 'numeric'], 'height_inches' => ['nullable', 'numeric'], 'weight_kg' => ['nullable', 'numeric'],
            'marital_status' => ['required', 'in:' . implode(',', self::MARITAL_STATUSES)],
            'date_of_birth' => ['required', 'date'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'mailing_address' => ['required', 'string'],
            'contact_numbers' => ['required', 'string', 'max:255'],
            'photograph' => ['nullable', 'image', 'mimes:gif,jpg,jpeg,png', 'max:2048'],
            'qualifications' => ['nullable', 'array'], 'qualifications.*.level' => ['nullable', 'string'],
            'qualifications.*.subjects' => ['nullable', 'string'], 'qualifications.*.institute' => ['nullable', 'string'],
            'qualifications.*.session' => ['nullable', 'string'], 'qualifications.*.grade' => ['nullable', 'string'],
            'qualifications.*.marks' => ['nullable', 'numeric'], 'qualifications.*.office_use' => ['nullable', 'string'],
            'previous_jobs' => ['nullable', 'array'], 'previous_jobs.*' => ['array'],
            'recent_experience' => ['nullable', 'array'], 'recent_experience.*' => ['array'],
        ]);

        if ($request->hasFile('photograph')) {
            $directory = public_path('uploads/job_photos');
            File::ensureDirectoryExists($directory);
            $data['photograph'] = $request->file('photograph')->move($directory, time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $request->file('photograph')->getClientOriginalName()))->getFilename();
        } elseif ($application) {
            $data['photograph'] = $application->photograph;
        }

        $data['qualifications'] = array_values($data['qualifications'] ?? []);
        $data['previous_jobs'] = array_values($data['previous_jobs'] ?? []);
        $data['recent_experience'] = array_values($data['recent_experience'] ?? []);
        return $data;
    }
}
