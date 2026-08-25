<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Hrms\JobAdvertisement;
use App\Models\Hrms\StaffDemand;
use App\Models\JobCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobAdvertisementController extends Controller
{
    private const TYPES = ['Visiting Part Time', 'Visiting Full Time', 'Temporary', 'Permanent'];

    public function index(): View
    {
        return view('admin.hrms.jobadvertisements.index', $this->pageData());
    }

    public function store(Request $request): RedirectResponse
    {
        JobAdvertisement::create($this->validatedData($request) + ['status' => 'active']);
        return to_route('admin.hrms.jobadvertisements.index')->with('success', 'Job advertisement created successfully.');
    }

    public function edit(int $jobAdvertisement): View
    {
        return view('admin.hrms.jobadvertisements.index', $this->pageData(JobAdvertisement::findOrFail($jobAdvertisement)));
    }

    public function update(Request $request, int $jobAdvertisement): RedirectResponse
    {
        JobAdvertisement::findOrFail($jobAdvertisement)->update($this->validatedData($request));
        return to_route('admin.hrms.jobadvertisements.index')->with('success', 'Job advertisement updated successfully.');
    }

    public function show(int $jobAdvertisement): View
    {
        return view('admin.hrms.jobadvertisements.index', $this->pageData(JobAdvertisement::findOrFail($jobAdvertisement), true));
    }

    public function destroy(int $jobAdvertisement): RedirectResponse
    {
        JobAdvertisement::findOrFail($jobAdvertisement)->delete();
        return to_route('admin.hrms.jobadvertisements.index')->with('success', 'Job advertisement deleted successfully.');
    }

    public function salaryRange(Request $request): JsonResponse
    {
        $demand = StaffDemand::query()->where('position', $request->string('position')->toString())->latest('id')->first();
        $type = $demand?->natureOfJob();
        $labels = ['visiting_part_time' => 'Visiting Part Time', 'visiting_full_time' => 'Visiting Full Time', 'temporary' => 'Temporary', 'permanent' => 'Permanent'];
        return response()->json(['salary' => $demand?->salary_range ?? '', 'employment_type' => $labels[$type] ?? '']);
    }

    public function updateStatus(Request $request, int $jobAdvertisement): JsonResponse
    {
        $data = $request->validate(['status' => ['required', 'in:active,inactive']]);
        JobAdvertisement::findOrFail($jobAdvertisement)->update($data);
        return response()->json(['success' => true]);
    }

    public function print(int $jobAdvertisement): View
    {
        return view('admin.hrms.jobadvertisements.print', ['advertisement' => JobAdvertisement::findOrFail($jobAdvertisement)]);
    }

    private function pageData(?JobAdvertisement $advertisement = null, bool $viewMode = false): array
    {
        return [
            'advertisements' => JobAdvertisement::query()->latest('created_at')->get(),
            'advertisement' => $advertisement,
            'viewMode' => $viewMode,
            'positions' => StaffDemand::query()->whereNotNull('position')->where('position', '!=', '')->distinct()->orderBy('position')->pluck('position'),
            'categories' => JobCategory::query()->orderBy('id')->get(),
            'campuses' => Branch::query()->where('is_active', 1)->whereHas('staff', fn ($q) => $q->where('is_active', 1))->orderBy('name')->get(['id', 'name']),
            'types' => self::TYPES,
        ];
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'job_title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:' . implode(',', self::TYPES)],
            'salary' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'deadline' => ['required', 'date'],
        ]);
        return $data;
    }
}
