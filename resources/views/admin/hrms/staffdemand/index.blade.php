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
@push('styles')
<style>
    .demand-toolbar{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:8px 12px;border-bottom:1px solid #d8dce5}
    .demand-search{width:220px;height:32px;border:1px solid #aeb8c6;border-radius:6px;padding:0 8px;font-size:12px;outline:0}
    .demand-search:focus{border-color:#264796}
    .demand-tools{display:flex;gap:4px}.demand-tools button{width:30px;height:30px;border:0;border-radius:6px;background:#62676d;color:#fff;font-size:13px;cursor:pointer}.demand-tools button:hover{background:#264796}
    .demand-head{border:1px solid #7d858e;padding:8px 9px;font-size:11px;font-weight:700;white-space:nowrap}.demand-head i{margin-left:3px;color:#e5e7eb;font-size:9px}
    .demand-cell{border:1px solid #bfc8d4;padding:7px 9px;vertical-align:middle;font-size:11px}.demand-record-count{padding-top:8px;font-size:10px;color:#344054}
    .staff-demand-records .action{width:27px;height:27px;font-size:12px;margin-right:3px}.staff-demand-records .action.view,.staff-demand-records .action.edit,.staff-demand-records .action.delete{border:0}
    .staff-demand-records .demand-table thead tr{background:#626262!important}
    .staff-demand-records .demand-table .demand-head{background:#626262!important;color:#fff!important;border:1px solid #858585!important;padding:7px 9px!important;font-size:11px!important;line-height:14px!important}
    .staff-demand-records .demand-table .demand-head i{color:#e5e7eb!important}
    .staff-demand-records .demand-cell{font-size:11px!important;line-height:15px!important}
    .staff-demand-records .action{width:27px!important;height:25px!important;min-width:27px!important;padding:0!important;margin:0 2px 0 0!important;border-radius:0!important;font-size:11px!important;line-height:25px!important}
    .staff-demand-records .demand-table{width:100%;table-layout:fixed}
    .staff-demand-records .demand-table th:nth-child(1),.staff-demand-records .demand-table td:nth-child(1){width:6%}
    .staff-demand-records .demand-table th:nth-child(2),.staff-demand-records .demand-table td:nth-child(2){width:14%}
    .staff-demand-records .demand-table th:nth-child(3),.staff-demand-records .demand-table td:nth-child(3){width:20%}
    .staff-demand-records .demand-table th:nth-child(4),.staff-demand-records .demand-table td:nth-child(4){width:19%}
    .staff-demand-records .demand-table th:nth-child(5),.staff-demand-records .demand-table td:nth-child(5){width:13%}
    .staff-demand-records .demand-table th:nth-child(6),.staff-demand-records .demand-table td:nth-child(6){width:13%}
    .staff-demand-records .demand-table th:nth-child(7),.staff-demand-records .demand-table td:nth-child(7){width:15%}
    .staff-demand-records .demand-cell{padding:5px 7px!important;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .staff-demand-records .demand-table td:last-child{overflow:visible;text-overflow:clip}
    .staff-demand-records .action{width:22px!important;height:22px!important;min-width:22px!important;margin-right:2px!important;font-size:10px!important;line-height:22px!important}
    @media(max-width:900px){.demand-toolbar{align-items:flex-start;flex-direction:column}.demand-search{width:100%}.demand-tools{align-self:flex-end}}
</style>
@endpush
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
@push('styles')<style>.staff-demand-layout{display:grid;grid-template-columns:minmax(360px,1fr) minmax(0,2fr);align-items:start;gap:12px}.label{display:block;margin-bottom:5px;font-size:13px;font-weight:600;color:#222}.label span{color:#e11d48}.input{width:100%;border:1px solid #cfd6e0;padding:7px 10px;font-size:13px;color:#333;outline:0}.input:focus{border-color:#264796}.error{display:block;color:#dc2626;font-size:11px;margin-top:3px}.button{display:inline-block;padding:7px 14px;font-size:12px;font-weight:600}.button.primary{background:#264796;color:#fff}.button.secondary{border:1px solid #cfd6e0;color:#333}.action{display:inline-flex;width:24px;height:24px;align-items:center;justify-content:center;margin-right:3px;color:#fff;font-weight:bold}.action.view{background:#17a2b8}.action.edit{background:#264796}.action.delete{border:0;background:#dc3545;cursor:pointer}.demand-popover{cursor:help;border-bottom:1px dotted #264796}@media (max-width:900px){.staff-demand-layout{grid-template-columns:1fr}}</style>@endpush
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
