@extends('admin.layouts.app')

@section('title', 'Master Timetable')

@section('content')
<section class="rounded border border-neutral-200 bg-white">
    <div class="border-b border-neutral-200 px-4 py-3"><h2 class="text-lg font-medium">Select Criteria</h2></div>
    <form method="GET" action="{{ route('admin.academics.master-timetables.index', absolute: false) }}" class="grid gap-4 p-4 md:grid-cols-3">
        <div><label class="mb-1 block text-sm">Branch <span class="required-mark">*</span></label><select name="brc_id" required class="w-full rounded border border-neutral-300 px-3 py-2"><option value="">Select</option>@foreach($branches as $item)<option value="{{ $item->id }}" @selected((string)$branchId === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
        <input type="hidden" name="searched" value="1">
        <div class="flex items-end"><button class="rounded bg-blue-800 px-4 py-2 text-sm text-white"><i class="fa fa-search"></i> Search</button></div>
    </form>
</section>

@if(request()->boolean('searched'))
<section class="mt-4 overflow-x-auto rounded border border-neutral-200 bg-white">
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-neutral-200 px-3 py-2"><h2 class="text-lg font-medium">Master Timetable List</h2><div class="flex items-center gap-1"><input type="search" data-table-search="master-timetable-table" placeholder="Search..." class="rounded border border-neutral-300 px-3 py-1.5 text-sm"><button type="button" data-table-export="copy" data-table="master-timetable-table" title="Copy" class="table-export-button"><i class="fa fa-copy"></i></button><button type="button" data-table-export="excel" data-table="master-timetable-table" title="Excel" class="table-export-button"><i class="fa fa-file-excel"></i></button><button type="button" data-table-export="csv" data-table="master-timetable-table" title="CSV" class="table-export-button"><i class="fa fa-file-csv"></i></button><button type="button" data-table-export="print" data-table="master-timetable-table" title="Print" class="table-export-button"><i class="fa fa-print"></i></button><button type="button" data-table-columns="master-timetable-table" title="Columns" class="table-export-button"><i class="fa fa-table-columns"></i></button></div></div>
    <table id="master-timetable-table" class="w-full text-left text-sm"><thead class="bg-blue-800 text-white"><tr><th class="cursor-pointer px-3 py-2">Branch ↕</th><th class="cursor-pointer px-3 py-2">Day ↕</th><th class="cursor-pointer px-3 py-2">Slot ↕</th><th class="cursor-pointer px-3 py-2">Class ↕</th><th class="cursor-pointer px-3 py-2">Section ↕</th><th class="cursor-pointer px-3 py-2">Subject ↕</th><th class="cursor-pointer px-3 py-2">Teacher ↕</th></tr></thead><tbody class="divide-y divide-neutral-200">@forelse($records as $record)<tr><td class="px-3 py-2">{{ $record->branch_name ?: '—' }}</td><td class="px-3 py-2">{{ $record->day }}</td><td class="px-3 py-2">{{ $record->slot_name ?: '—' }}@if($record->start_time)<small class="block text-neutral-500">{{ date('g:i A', strtotime($record->start_time)) }} - {{ date('g:i A', strtotime($record->end_time)) }}</small>@endif</td><td class="px-3 py-2">{{ $record->class_name ?: '—' }}</td><td class="px-3 py-2">{{ $record->section_name ?: '—' }}</td><td class="px-3 py-2">{{ $record->subject_name ?: '—' }}</td><td class="px-3 py-2">{{ $record->teacher_name ?: '—' }}</td></tr>@empty<tr><td colspan="7" class="px-3 py-8 text-center text-neutral-500">No timetable found for this branch.</td></tr>@endforelse</tbody></table>
    <div class="flex justify-between px-3 py-3 text-xs text-neutral-600"><span>Records: {{ $records->count() ? 1 : 0 }} to {{ $records->count() }} of {{ $records->count() }}</span><span class="list-pagination"><button type="button" disabled aria-label="Previous page"><i class="fa fa-angle-left"></i></button><span>1</span><button type="button" disabled aria-label="Next page"><i class="fa fa-angle-right"></i></button></span></div>
</section>
@endif
@endsection
