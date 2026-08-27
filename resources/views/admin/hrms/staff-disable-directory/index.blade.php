@extends('admin.layouts.app')

@section('title', 'Staff Disable Directory')


@section('content')
<div class="disable-directory-page">
    <section class="disable-directory-card">
        <div class="disable-directory-title">Select Criteria</div>
        <form method="GET" action="{{ route('admin.hrms.staff-disable-directory.index', absolute: false) }}" class="disable-directory-form">
            <div class="disable-filter-grid">
                <label class="disable-field">Branch <span class="text-red-500">*</span>
                    <select name="brc_id" onchange="this.form.submit()" class="mt-1 h-9 w-full border border-[#cfd6e0] bg-white px-2">
                        @foreach($branches as $branch)<option value="{{ $branch->id }}" @selected((int)$selectedBranchId === (int)$branch->id)>{{ $branch->name }}</option>@endforeach
                    </select>
                </label>
                <label class="disable-field">Role
                    <select name="role" class="mt-1 h-9 w-full border border-[#cfd6e0] bg-white px-2">
                        <option value="">Select</option>
                        @foreach($roles as $role)<option value="{{ $role['id'] }}" @selected((int)$selectedRoleId === (int)$role['id'])>{{ $role['name'] }}</option>@endforeach
                    </select>
                </label>
                <label class="disable-field">Branch <span class="text-red-500">*</span>
                    <select name="brc_id_display" disabled class="mt-1 h-9 w-full border border-[#cfd6e0] bg-white px-2"><option>{{ $branches->firstWhere('id', $selectedBranchId)?->name ?? 'Main' }}</option></select>
                </label>
                <label class="disable-field disable-search-field">Search By Keyword <span class="text-red-500">*</span>
                    <div class="disable-search">
                        <select name="selected_value_staff_dis">
                            <option value="staff_id" @selected($selectedSearchField === 'staff_id')>Staff ID</option><option value="name" @selected($selectedSearchField === 'name')>Name</option><option value="role" @selected($selectedSearchField === 'role')>Role</option>
                        </select>
                        <input name="text_staff_dis" value="{{ $searchText }}" placeholder="Search By Staff ID, Name, Role etc...">
                        <button class="bg-[#62666b] px-3 font-semibold text-white"><i class="fa fa-search"></i> Search</button>
                    </div>
                </label>
            </div>
            <div class="disable-submit-wrap"><button class="disable-submit"><i class="fa fa-search"></i> Search</button></div>
        </form>
    </section>

    @if(session('success'))<div class="mb-3 border border-green-200 bg-green-50 px-3 py-2 text-green-700">{{ session('success') }}</div>@endif
    <section class="disable-directory-card">
        <div class="disable-directory-title">{{ $searchText !== '' ? 'Search Details: '.$searchText : 'Disable Staff List' }}</div>
        <div class="disable-table-wrap"><table class="disable-table"><thead><tr><th>Campus</th><th>Staff ID</th><th>Name</th><th>Role</th><th>Department</th><th>Designation</th><th>Mobile No.</th><th>Action</th></tr></thead><tbody>
            @forelse($records as $staff)<tr><td>{{ $staff->branch_name ?: '-' }}</td><td>{{ $staff->employee_id ?: '-' }}</td><td><a style="color:#173d83;text-decoration:underline" href="{{ route('admin.hrms.staff.profile', $staff->id, absolute: false) }}">{{ trim($staff->name.' '.($staff->surname ?? '')) }}</a></td><td>{{ $staff->role_name ?: '-' }}</td><td>{{ $staff->department_name ?: '-' }}</td><td>{{ $staff->designation_name ?: '-' }}</td><td>{{ $staff->contact_no ?: '-' }}</td><td><div class="disable-action"><a title="View" href="{{ route('admin.hrms.staff.profile', $staff->id, absolute: false) }}"><i class="fa fa-eye"></i></a><form method="POST" action="{{ route('admin.hrms.staff-disable-directory.enable', $staff->id, absolute: false) }}" onsubmit="return confirm('Enable this staff member?')">@csrf<button title="Enable"><i class="fa fa-check"></i></button></form></div></td></tr>@empty<tr><td colspan="8" style="text-align:center">No record found</td></tr>@endforelse
        </tbody></table><div>{{ $records->links() }}</div></div>
    </section>
</div>
@endsection
