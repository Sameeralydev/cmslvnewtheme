@extends('admin.layouts.app')
@section('title', 'Import Staff')
@section('content')
<section class="mx-auto max-w-5xl rounded-md border border-[#d8dce5] bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-[#d8dce5] px-4 py-3"><h2 class="text-[14px] font-medium">Staff Import</h2><a href="{{ route('admin.hrms.staff.index', ['brc_id' => $selectedBranchId], false) }}" class="text-[11px] text-[#264796]">Back to Staff Directory</a></div>
    <div class="p-4 text-[11px] text-[#475467]"><p class="mb-3">CSV headers can include: <code>employee_id, name, surname, father_name, email, gender, dob, date_of_joining, contact_no, emergency_contact_no, marital_status, local_address, permanent_address, note</code>.</p>
        @if (session('success')) <div class="mb-3 border border-green-200 bg-green-50 px-3 py-2 text-green-700">{{ session('success') }}</div> @endif
        <form method="POST" action="{{ route('admin.hrms.staff.import', absolute: false) }}" enctype="multipart/form-data" class="grid gap-3 md:grid-cols-3">@csrf
            <div><label class="mb-1 block font-semibold">Branch *</label><select name="brc_id" class="h-9 w-full border px-2" required>@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected($selectedBranchId === (int)$branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
            <div><label class="mb-1 block font-semibold">Role *</label><select name="role_id" class="h-9 w-full border px-2" required><option value="">Select</option>@foreach($roles as $role)<option value="{{ $role['id'] }}">{{ $role['name'] }}</option>@endforeach</select></div>
            <div><label class="mb-1 block font-semibold">Select CSV File *</label><input type="file" name="file" accept=".csv,text/csv" class="h-9 w-full border px-2" required></div>
            <div class="md:col-span-3"><button class="bg-[#264796] px-4 py-2 font-semibold text-white">Import Staff</button></div>
        </form>
    </div>
</section>
@endsection
