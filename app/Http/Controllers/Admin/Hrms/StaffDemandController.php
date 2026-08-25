<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Hrms\Staff;
use App\Models\Hrms\StaffDemand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffDemandController extends Controller
{
    private const NATURES = [
        'visiting_part_time' => 'Visiting Part Time',
        'visiting_full_time' => 'Visiting Full Time',
        'temporary' => 'Temporary',
        'permanent' => 'Permanent',
    ];

    private const POSITIONS = ['Senior Teacher', 'Junior Teacher'];

    private const ROLES = [
        'Super Admin', 'Chairman', 'Project Director', 'Finance Director',
        'Academics Director', 'IT Director', 'Director', 'Principal',
        'Administration & HRM', 'Co-ordinator', 'Receptionist', 'Accountant',
        'Librarian', 'Teacher', 'Assistant Teacher', 'PRO', 'IT Manager',
        'Composer', 'Gatekeeper', 'Lower Staff',
    ];

    public function index(): View
    {
        return view('admin.hrms.staffdemand.index', $this->pageData());
    }

    public function store(Request $request): RedirectResponse
    {
        StaffDemand::create($this->validatedData($request));
        return to_route('admin.hrms.staffdemand.index')->with('success', 'Staff demand saved successfully.');
    }

    public function edit(int $staffDemand): View
    {
        return view('admin.hrms.staffdemand.index', $this->pageData(StaffDemand::findOrFail($staffDemand)));
    }

    public function update(Request $request, int $staffDemand): RedirectResponse
    {
        StaffDemand::findOrFail($staffDemand)->update($this->validatedData($request));
        return to_route('admin.hrms.staffdemand.index')->with('success', 'Staff demand updated successfully.');
    }

    public function show(int $staffDemand): View
    {
        return view('admin.hrms.staffdemand.index', $this->pageData(StaffDemand::findOrFail($staffDemand), true));
    }

    public function destroy(int $staffDemand): RedirectResponse
    {
        StaffDemand::findOrFail($staffDemand)->delete();
        return to_route('admin.hrms.staffdemand.index')->with('success', 'Staff demand deleted successfully.');
    }

    public function staffByCampus(Request $request): JsonResponse
    {
        $staff = Staff::query()->where('brc_id', $request->integer('campus_id'))
            ->where('is_active', 1)->orderBy('name')->orderBy('surname')
            ->get(['id', 'name', 'surname', 'employee_id']);
        return response()->json($staff);
    }

    private function pageData(?StaffDemand $demand = null, bool $viewMode = false): array
    {
        if ($demand !== null) {
            $requester = Staff::query()->find($demand->requester_name);
            $demand->setAttribute('staff_name', $requester?->name ?? '');
            $demand->setAttribute('staff_surname', $requester?->surname ?? '');
            $demand->setAttribute('employee_id', $requester?->employee_id ?? '');
        }

        $campuses = Branch::query()->where('is_active', 1)
            ->whereHas('staff', fn ($q) => $q->where('is_active', 1))
            ->orderBy('name')->get(['id', 'name']);

        return [
            'demands' => StaffDemand::query()->leftJoin('branch', 'branch.id', '=', 'staff_demand_forms.campus')
                ->leftJoin('staff', 'staff.id', '=', 'staff_demand_forms.requester_name')
                ->orderByDesc('staff_demand_forms.id')
                ->select('staff_demand_forms.*', 'branch.name as campus_name', 'staff.name as staff_name', 'staff.surname as staff_surname', 'staff.employee_id')
                ->get(),
            'campuses' => $campuses,
            'demand' => $demand,
            'viewMode' => $viewMode,
            'positions' => self::POSITIONS,
            'natures' => self::NATURES,
            'roles' => self::ROLES,
            'today' => now()->toDateString(),
        ];
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'requesterName' => ['required', 'integer', 'exists:staff,id'],
            'staffRequired' => ['required', 'integer', 'min:1'],
            'campus' => ['required', 'integer', 'exists:branch,id'],
            'demandDate' => ['required', 'date'],
            'position' => ['required', 'string', 'in:' . implode(',', self::POSITIONS)],
            'nature_of_job' => ['required', 'string', 'in:' . implode(',', array_keys(self::NATURES))],
            'academicQualifications' => ['required', 'string'],
            'professionalQualifications' => ['nullable', 'string'],
            'role' => ['required', 'string', 'in:' . implode(',', self::ROLES)],
            'experience' => ['required', 'string'],
            'expectedSkills' => ['nullable', 'string'],
            'expectedAttitude' => ['nullable', 'string'],
            'ageRange' => ['nullable', 'string'],
            'salaryRange' => ['nullable', 'string'],
        ]);

        $nature = $data['nature_of_job'];
        return [
            'requester_name' => $data['requesterName'], 'designation' => '', 'department' => '',
            'staff_required' => $data['staffRequired'], 'campus' => $data['campus'],
            'demand_date' => $data['demandDate'], 'position' => $data['position'],
            'visiting_part_time' => (int) ($nature === 'visiting_part_time'),
            'visiting_full_time' => (int) ($nature === 'visiting_full_time'),
            'temporary' => (int) ($nature === 'temporary'), 'permanent' => (int) ($nature === 'permanent'),
            'academic_qualifications' => $data['academicQualifications'],
            'professional_qualifications' => $data['professionalQualifications'] ?? '',
            'role' => $data['role'], 'experience' => $data['experience'],
            'expected_skills' => $data['expectedSkills'] ?? '', 'expected_attitude' => $data['expectedAttitude'] ?? '',
            'age_range' => $data['ageRange'] ?? '', 'salary_range' => $data['salaryRange'] ?? '',
            'file_code' => '', 'revision' => 0, 'form_department' => '',
        ];
    }
}
