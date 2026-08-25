<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Hrms\Staff;
use App\Models\RoleBranch;
use App\Services\BranchContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffDisableDirectoryController extends Controller
{
    public function __construct(private readonly BranchContext $branchContext) {}

    public function index(Request $request): View
    {
        $branchId = $this->resolveBranchId($request);
        $branches = Branch::query()->orderBy('name')->get(['id', 'name']);
        $roles = $this->rolesForBranch($branchId);
        $records = $this->disabledStaffQuery($request, $branchId)->paginate(20)->withQueryString();

        return view('admin.hrms.staff-disable-directory.index', [
            'branches' => $branches,
            'roles' => $roles,
            'records' => $records,
            'selectedBranchId' => $branchId,
            'selectedRoleId' => $request->integer('role') ?: null,
            'selectedSearchField' => $request->string('selected_value_staff_dis')->toString() ?: 'staff_id',
            'searchText' => trim($request->string('text_staff_dis')->toString()),
        ]);
    }

    public function enable(Staff $staff): RedirectResponse
    {
        $staff->forceFill(['is_active' => 1, 'disable_at' => null])->save();

        return back()->with('success', 'Staff member enabled successfully.');
    }

    private function resolveBranchId(Request $request): ?int
    {
        $branchId = $request->integer('brc_id');

        return $branchId > 0
            ? $branchId
            : ($this->branchContext->id() ?: Branch::query()->orderBy('id')->value('id'));
    }

    private function rolesForBranch(?int $branchId): \Illuminate\Support\Collection
    {
        if ($branchId === null) return collect();

        return RoleBranch::query()->with('role:id,name')->forBranch($branchId)->active()->orderBy('id')
            ->get(['id', 'roles_id', 'brc_id'])
            ->map(fn (RoleBranch $role): array => ['id' => $role->id, 'name' => trim((string) $role->role?->name)])
            ->filter(fn (array $role): bool => $role['name'] !== '')->values();
    }

    private function disabledStaffQuery(Request $request, ?int $branchId): Builder
    {
        $field = $request->string('selected_value_staff_dis')->toString() ?: 'staff_id';
        $text = trim($request->string('text_staff_dis')->toString());

        return Staff::query()->selectRaw('staff.*, branch.name as branch_name, designation.name as designation_name, department.name as department_name, roles.name as role_name')
            ->leftJoin('branch', 'branch.id', '=', 'staff.brc_id')
            ->leftJoin('designation', 'designation.id', '=', 'staff.designation')
            ->leftJoin('department', 'department.id', '=', 'staff.department')
            ->leftJoin('roles_branch', 'roles_branch.id', '=', 'staff.role_id')
            ->leftJoin('roles', 'roles.id', '=', 'roles_branch.roles_id')
            ->where('staff.is_active', 0)
            ->when($branchId !== null, fn (Builder $query) => $query->where('staff.brc_id', $branchId))
            ->when($request->filled('role'), fn (Builder $query) => $query->where('staff.role_id', $request->integer('role')))
            ->when($text !== '', function (Builder $query) use ($field, $text): void {
                if ($field === 'name') {
                    $query->where(fn (Builder $nested) => $nested->where('staff.name', 'like', "%{$text}%")->orWhere('staff.surname', 'like', "%{$text}%"));
                } elseif ($field === 'role') {
                    $query->where('roles.name', 'like', "%{$text}%");
                } else {
                    $query->where('staff.employee_id', $text);
                }
            })
            ->orderBy('staff.id');
    }
}
