@extends('admin.layouts.app')

@section('title', $viewMode ? 'View Staff Demand' : ($demand ? 'Edit Staff Demand' : 'Staff Demand'))

@php
    $editing = $demand !== null && !$viewMode;
    $value = fn ($key, $default = '') => old($key, $demand?->{$key} ?? $default);
    $nature = $demand?->natureOfJob() ?? old('nature_of_job', '');
    $selectedCampus = (string) $value('campus');
    $selectedRequester = (string) $value('requester_name', $demand?->requester_name ?? '');
@endphp

@section('content')
<div class="staff-demand-layout items-start gap-3">
    <section class="overflow-hidden rounded-md border border-[#d8dce5] bg-white shadow-sm">
        <div class="border-b border-[#d8dce5] px-4 py-3"><h2 class="text-[16px] font-medium text-[#222]">{{ $viewMode ? 'View Staff Demand' : ($editing ? 'Edit Staff Demand' : 'Add Staff Demand') }}</h2></div>
        <form method="POST" action="{{ $editing ? route('admin.hrms.staffdemand.update', $demand->id, false) : route('admin.hrms.staffdemand.store', absolute: false) }}" class="px-4 py-3">
            @csrf
            @if($editing) @method('PUT') @endif
            @if(session('success')) <div class="mb-3 border border-green-200 bg-green-50 px-3 py-2 text-[12px] text-green-700">{{ session('success') }}</div> @endif
            @if($errors->any()) <div class="mb-3 border border-red-200 bg-red-50 px-3 py-2 text-[12px] text-red-700">Please correct the highlighted fields.</div> @endif

            @php $fields = [
                ['name'=>'staffRequired','label'=>'Staff Required','type'=>'number','required'=>true,'value'=>$value('staffRequired', $demand?->staff_required ?? ''), 'attr'=>'min="1"'],
                ['name'=>'demandDate','label'=>'Demand Date','type'=>'date','required'=>true,'value'=>$value('demandDate', $demand?->demand_date?->format('Y-m-d') ?? $today)],
            ]; @endphp
            <div class="mb-3"><label class="label">Branch <span>*</span></label><select name="campus" id="campus_select" class="input" {{ $viewMode ? 'disabled' : 'required' }}><option value="">Select Campus</option>@foreach($campuses as $campus)<option value="{{ $campus->id }}" @selected($selectedCampus === (string)$campus->id)>{{ $campus->name }}</option>@endforeach</select>@error('campus')<small class="error">{{ $message }}</small>@enderror</div>
            <div class="mb-3"><label class="label">Requester Name <span>*</span></label>@if($viewMode)<input class="input" readonly value="{{ trim(($demand->staff_name ?? '') . ' ' . ($demand->staff_surname ?? '')) }} ({{ $demand->employee_id ?? '' }})">@else<select name="requesterName" id="requester_select" class="input" required><option value="">Select Campus First</option></select>@endif @error('requesterName')<small class="error">{{ $message }}</small>@enderror</div>
            @foreach($fields as $field)<div class="mb-3"><label class="label">{{ $field['label'] }} <span>*</span></label><input name="{{ $field['name'] }}" type="{{ $field['type'] }}" value="{{ $field['value'] }}" class="input" {{ $field['attr'] ?? '' }} {{ $viewMode ? 'readonly' : ($field['required'] ? 'required' : '') }}>@error($field['name'])<small class="error">{{ $message }}</small>@enderror</div>@endforeach
            <div class="mb-3"><label class="label">Position <span>*</span></label>@if($viewMode)<input class="input" readonly value="{{ $demand->position ?? '' }}">@else<select name="position" class="input" required><option value="">Select Position</option>@foreach($positions as $item)<option @selected($value('position') === $item)>{{ $item }}</option>@endforeach</select>@endif</div>
            <div class="mb-3"><label class="label">Nature of Job <span>*</span></label>@if($viewMode)<input class="input" readonly value="{{ $natures[$nature] ?? '' }}">@else<select name="nature_of_job" class="input" required><option value="">Select Nature of Job</option>@foreach($natures as $key=>$label)<option value="{{ $key }}" @selected($nature === $key)>{{ $label }}</option>@endforeach</select>@endif</div>
            <div class="mb-3"><label class="label">Academic Qualifications <span>*</span></label><textarea name="academicQualifications" class="input" rows="3" {{ $viewMode ? 'readonly' : 'required' }}>{{ $value('academicQualifications', $demand?->academic_qualifications ?? '') }}</textarea></div>
            <div class="mb-3"><label class="label">Professional Qualifications</label><textarea name="professionalQualifications" class="input" rows="3" {{ $viewMode ? 'readonly' : '' }}>{{ $value('professionalQualifications', $demand?->professional_qualifications ?? '') }}</textarea></div>
            <div class="mb-3"><label class="label">Role <span>*</span></label>@if($viewMode)<input class="input" readonly value="{{ $demand->role ?? '' }}">@else<select name="role" class="input" required><option value="">Select Role</option>@foreach($roles as $item)<option @selected($value('role') === $item)>{{ $item }}</option>@endforeach</select>@endif</div>
            @foreach([['experience','Experience',true],['expectedSkills','Expected Skills',false],['expectedAttitude','Expected Attitude',false],['ageRange','Age Range',false],['salaryRange','Salary Range',false]] as [$name,$label,$required])<div class="mb-3"><label class="label">{{ $label }} @if($required)<span>*</span>@endif</label><textarea name="{{ $name }}" class="input" rows="2" {{ $viewMode ? 'readonly' : '' }} {{ $required && !$viewMode ? 'required' : '' }}>{{ $value($name, $demand?->{strtolower(preg_replace('/([A-Z])/', '_$1', $name))} ?? '') }}</textarea></div>@endforeach
            @if(!$viewMode)<div class="flex justify-end gap-2 pt-2"><a href="{{ route('admin.hrms.staffdemand.index', absolute: false) }}" class="button secondary">{{ $editing ? 'Cancel' : 'Reset' }}</a><button class="button primary" type="submit">{{ $editing ? 'Update' : 'Save' }}</button></div>@else<div class="flex justify-end gap-2 pt-2"><a href="{{ route('admin.hrms.staffdemand.index', absolute: false) }}" class="button secondary">Back to List</a><a href="{{ route('admin.hrms.staffdemand.edit', $demand->id, false) }}" class="button primary">Edit</a></div>@endif
        </form>
    </section>

    <section class="staff-demand-records overflow-hidden rounded-md border border-[#d8dce5] bg-white shadow-sm"><div class="border-b border-[#d8dce5] px-4 py-3"><h2 class="text-[16px] font-medium text-[#222]">Staff Demand List</h2></div><div class="demand-toolbar"><input id="demand_table_search" type="search" placeholder="Search..." class="demand-search"><div class="demand-tools"><button type="button" title="Copy"><i class="fa fa-copy"></i></button><button type="button" title="Excel"><i class="fa fa-file-excel-o"></i></button><button type="button" title="CSV"><i class="fa fa-file-text-o"></i></button><button type="button" title="PDF"><i class="fa fa-file-pdf-o"></i></button><button type="button" title="Print" onclick="window.print()"><i class="fa fa-print"></i></button><button type="button" title="Columns"><i class="fa fa-columns"></i></button></div></div><div class="overflow-x-auto p-3"><table id="demand_table" class="demand-table min-w-full border-collapse text-left text-[11px]"><thead class="bg-[#626262] text-white"><tr><th class="demand-head">ID <i class="fa fa-caret-down"></i></th><th class="demand-head">Campus <i class="fa fa-caret-down"></i></th><th class="demand-head">Requester <i class="fa fa-caret-down"></i></th><th class="demand-head">Position <i class="fa fa-caret-down"></i></th><th class="demand-head">Staff Required <i class="fa fa-caret-down"></i></th><th class="demand-head">Date <i class="fa fa-caret-down"></i></th><th class="demand-head">Action</th></tr></thead><tbody>@forelse($demands as $item)<tr class="demand-row"><td class="demand-cell">{{ $loop->iteration }}</td><td class="demand-cell">{{ $item->campus_name }}</td><td class="demand-cell"><span class="demand-popover" title="Details" data-detail="Designation: {{ $item->designation }} | Department: {{ $item->department }} | Nature: {{ $item->natureOfJob() }}">{{ trim(($item->staff_name ?? '') . ' ' . ($item->staff_surname ?? '')) }} ({{ $item->employee_id ?? '' }})</span></td><td class="demand-cell">{{ $item->position }}</td><td class="demand-cell">{{ $item->staff_required }}</td><td class="demand-cell">{{ $item->demand_date?->format('Y-m-d') }}</td><td class="demand-cell whitespace-nowrap"><a class="action view" title="View" href="{{ route('admin.hrms.staffdemand.show', $item->id, false) }}"><i class="fa fa-eye"></i></a><a class="action edit" title="Edit" href="{{ route('admin.hrms.staffdemand.edit', $item->id, false) }}"><i class="fa fa-pencil"></i></a><form class="inline" method="POST" action="{{ route('admin.hrms.staffdemand.destroy', $item->id, false) }}" onsubmit="return confirm('Are you sure you want to delete this staff demand?')">@csrf @method('DELETE')<button class="action delete" title="Delete"><i class="fa fa-times"></i></button></form></td></tr>@empty<tr><td colspan="7" class="demand-cell py-4 text-center">No staff demands found</td></tr>@endforelse</tbody></table><div class="demand-record-count">Records: {{ $demands->count() ? 1 : 0 }} to {{ $demands->count() }} of {{ $demands->count() }}</div></div></section>
</div>
@push('scripts')<script>document.addEventListener('DOMContentLoaded',()=>{const campus=document.getElementById('campus_select'), requester=document.getElementById('requester_select');if(!campus||!requester)return;const selected=@json($selectedRequester);const load=()=>{requester.innerHTML='<option value="">Loading...</option>';if(!campus.value){requester.innerHTML='<option value="">Select Campus First</option>';return}fetch(@json(route('admin.hrms.staffdemand.staff-by-campus', absolute: false)),{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Accept':'application/json'},body:JSON.stringify({campus_id:campus.value})}).then(r=>r.json()).then(rows=>{requester.innerHTML='<option value="">Select Requester</option>';rows.forEach(staff=>{const o=new Option(`${staff.name} ${staff.surname||''} (${staff.employee_id||''})`,staff.id);o.selected=String(staff.id)===selected;requester.add(o)})}).catch(()=>requester.innerHTML='<option value="">Error loading staff</option>')};campus.addEventListener('change',load);if(campus.value)load()});</script>@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('demand_table_search');
    if (!search) return;
    search.addEventListener('input', function () {
        const term = this.value.toLowerCase().trim();
        document.querySelectorAll('#demand_table .demand-row').forEach(function (row) {
            row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    });
});
</script>
@endpush
@endsection
