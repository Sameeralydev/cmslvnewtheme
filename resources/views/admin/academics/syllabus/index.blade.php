@extends('admin.layouts.app')

@section('title', 'Syllabus')

@section('content')
    <section id="syllabus-criteria-modal" class="mb-4 rounded border border-neutral-200 bg-white">
        <div>
        <div class="border-b border-neutral-200 px-4 py-3">
            <div><h2 class="text-lg font-medium">Select Criteria</h2></div>
        </div>
        <form method="GET" action="{{ route('admin.academics.syllabus.index', absolute: false) }}" class="p-4">
            <input type="hidden" name="searched" value="1">
            <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-6">
                <div>
                    <label class="mb-1 block text-sm">Branch <span class="text-red-600">*</span></label>
                    <select id="syllabus-branch" name="brc_id" required class="w-full rounded border border-neutral-300 px-3 py-2">
                        <option value="">Select</option>
                        @foreach($branches as $branch)<option value="{{ $branch->id }}" @selected((string)($selected['brc_id'] ?? '') === (string)$branch->id)>{{ $branch->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm">Term <span class="text-red-600">*</span></label>
                    <select name="term_id" required class="w-full rounded border border-neutral-300 px-3 py-2">
                        <option value="">Select</option>
                        @foreach($terms as $term)<option value="{{ $term->id }}" @selected((string)($selected['term_id'] ?? '') === (string)$term->id)>{{ $term->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm">Months <span class="text-red-600">*</span></label>
                    <select name="month_id" required class="w-full rounded border border-neutral-300 px-3 py-2">
                        <option value="">Select</option>
                        @foreach($months as $id => $month)<option value="{{ $id }}" @selected((string)($selected['month_id'] ?? '') === (string)$id)>{{ $month }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm">Class <span class="text-red-600">*</span></label>
                    <select id="syllabus-class" name="class_id" required class="w-full rounded border border-neutral-300 px-3 py-2">
                        <option value="">Select</option>
                        @foreach($classes as $class)<option value="{{ $class->id }}" @selected((string)($selected['class_id'] ?? '') === (string)$class->id)>{{ $class->class }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm">Section <span class="text-red-600">*</span></label>
                    <select id="syllabus-section" name="section_id" required class="w-full rounded border border-neutral-300 px-3 py-2">
                        <option value="">Select</option>
                        @foreach($sections as $section)<option value="{{ $section->id }}" @selected((string)($selected['section_id'] ?? '') === (string)$section->id)>{{ $section->section }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm">Subject <span class="text-red-600">*</span></label>
                    <select id="syllabus-subject" name="subject_id" required class="w-full rounded border border-neutral-300 px-3 py-2">
                        <option value="">Select</option>
                        @foreach($subjects as $subject)<option value="{{ $subject->id }}" @selected((string)($selected['subject_id'] ?? '') === (string)$subject->id)>{{ $subject->name }} ({{ $subject->code }})</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button class="rounded bg-blue-800 px-4 py-2 text-sm text-white hover:bg-blue-700"><i class="fa fa-search"></i> Search</button>
            </div>
        </form>
        </div>
    </section>

    @if(request()->boolean('searched'))
        <section id="syllabus-entry-modal" data-modal="syllabus-entry" class="admin-modal syllabus-entry-modal {{ session('toast_message') ? '' : 'is-open' }}">
        <div class="admin-modal-panel rounded border border-neutral-200 bg-white">
        <div class="border-b border-neutral-200 px-4 py-3"><div class="flex items-center justify-between"><h2 class="text-lg font-medium">Add Syllabus</h2><button type="button" class="admin-modal-close" data-modal-close="syllabus-entry" aria-label="Close">&times;</button></div></div>
        <form method="POST" action="{{ route('admin.academics.syllabus.store', absolute: false) }}" enctype="multipart/form-data" class="p-4">
            @csrf
            @foreach(['brc_id','term_id','month_id','class_id','section_id','subject_id'] as $field)<input type="hidden" name="{{ $field }}" value="{{ $selected[$field] ?? '' }}">@endforeach
            <div class="border-b border-neutral-200 bg-neutral-100 px-3 py-2 text-lg">Chapter/Topic Information</div>
            <div class="max-h-64 overflow-y-auto px-2 py-3">
                @forelse($chapters as $chapter)
                    <div class="mb-2 border-b border-neutral-200 pb-2">
                        <label class="flex items-center gap-2 font-medium"><input type="checkbox" name="chapters_id[]" value="{{ $chapter->id }}"> {{ $chapter->name }} @if($chapter->name_urdu)<span class="text-sm text-neutral-500">({{ $chapter->name_urdu }})</span>@endif</label>
                        @foreach($chapter->topics as $topic)
                            <label class="ml-6 mt-1 flex items-center gap-2 text-sm"><input type="checkbox" name="topics_id[]" value="{{ $topic->id }}"> {{ $topic->name }} @if($topic->name_urdu)<span class="text-neutral-500">({{ $topic->name_urdu }})</span>@endif</label>
                        @endforeach
                    </div>
                @empty
                    <p class="text-sm text-neutral-500">No chapters or topics found for this subject.</p>
                @endforelse
            </div>
            <div class="mt-3 border-b border-t border-neutral-200 bg-neutral-100 px-3 py-2 text-lg">Syllabus Information</div>
            <label class="mt-3 block text-sm">Syllabus <span class="text-red-600">*</span></label>
            <textarea name="presentation" required class="mt-1 min-h-24 w-full rounded border border-neutral-300 px-3 py-2"></textarea>
            <div class="mt-3 border-b border-t border-neutral-200 bg-neutral-100 px-3 py-2 text-lg">Video Lecture/Materials Information</div>
            <div class="mt-3 grid gap-4 md:grid-cols-2">
                <div><label class="block text-sm">Lecture YouTube URL</label><input type="url" name="vid_url" value="" class="mt-1 w-full rounded border border-neutral-300 px-3 py-2"></div>
                <div><label class="block text-sm">Material Attachment <span class="text-xs text-red-600">File Format PDF Only</span></label><input type="file" name="file" accept="application/pdf" class="syllabus-file-input mt-1 w-full rounded border border-neutral-300 px-3 py-2"></div>
            </div>
            <div class="mt-4 flex justify-end"><button class="rounded bg-blue-800 px-4 py-2 text-sm text-white hover:bg-blue-700">Save</button></div>
        </form>
        </div>
        </section>
    @endif

    @if(request()->boolean('searched'))
        <section class="mt-4 overflow-x-auto rounded border border-neutral-200 bg-white">
            <div class="border-b border-neutral-200 px-4 py-3"><div class="flex flex-wrap items-center justify-between gap-2"><h2 class="text-lg font-medium">Syllabus List</h2><div class="syllabus-directory-toolbar"><input type="search" data-table-search="syllabus-results" placeholder="Search..." class="rounded border border-neutral-300 px-3 py-2"><div class="flex gap-1"><button type="button" data-table-export="copy" data-table="syllabus-results" title="Copy"><i class="fa fa-copy"></i></button><button type="button" data-table-export="excel" data-table="syllabus-results" title="Excel"><i class="fa fa-file-excel"></i></button><button type="button" data-table-export="csv" data-table="syllabus-results" title="CSV"><i class="fa fa-file-csv"></i></button><a href="{{ route('admin.academics.syllabus.pdf', request()->query(), false) }}" title="PDF"><i class="fa fa-file-pdf"></i></a><button type="button" data-table-export="print" data-table="syllabus-results" title="Print"><i class="fa fa-print"></i></button><button type="button" data-table-columns="syllabus-results" title="Columns"><i class="fa fa-table-columns"></i></button></div></div></div></div>
            <table id="syllabus-results" class="w-full text-left text-sm">
                <thead class="bg-blue-800 text-white"><tr><th class="px-3 py-2">Branch ▼</th><th class="px-3 py-2">Term ▼</th><th class="px-3 py-2">Months ▼</th><th class="px-3 py-2">Class ▼</th><th class="px-3 py-2">Section ▼</th><th class="px-3 py-2">Subject ▼</th><th class="px-3 py-2">Syllabus ▼</th><th class="px-3 py-2">Status ▼</th></tr></thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($records as $record)
                        <tr><td class="px-3 py-2">{{ $record->branch_name }}</td><td class="px-3 py-2">{{ $record->term_name }}</td><td class="px-3 py-2">{{ $months[$record->month_id] ?? $record->month_id }}</td><td class="px-3 py-2">{{ $record->class_name }}</td><td class="px-3 py-2">{{ $record->section_name }}</td><td class="px-3 py-2">{{ $record->subject_name }}</td><td class="max-w-md px-3 py-2">{{ strip_tags((string) $record->presentation) }}</td><td class="px-3 py-2">{{ $record->status ? 'Active' : 'Inactive' }}</td></tr>
                    @empty
                        <tr><td colspan="8" class="px-3 py-8 text-center text-neutral-500">No syllabus found for the selected criteria.</td></tr>
                    @endforelse
                </tbody>
            </table><div class="px-3 py-2 text-xs text-neutral-600">Records: {{ $records->count() ? 1 : 0 }} to {{ $records->count() }} of {{ $records->count() }}</div>
        </section>
    @endif
@endsection

@push('scripts')
<script>
(function () {
    const classSelect = document.getElementById('syllabus-class');
    const sectionSelect = document.getElementById('syllabus-section');
    const subjectSelect = document.getElementById('syllabus-subject');
    if (!classSelect || !sectionSelect || !subjectSelect) return;
    const initialSection = @json($selected['section_id'] ?? '');
    const initialSubject = @json($selected['subject_id'] ?? '');
    const fill = (select, items, label, selected) => {
        select.innerHTML = '<option value="">Select</option>';
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = label === 'subject' ? `${item.name} (${item.code})` : item.section;
            if (String(item.id) === String(selected)) option.selected = true;
            select.appendChild(option);
        });
    };
    const load = (selectedSection = '', selectedSubject = '') => {
        if (!classSelect.value) { fill(sectionSelect, [], 'section', ''); fill(subjectSelect, [], 'subject', ''); return; }
        sectionSelect.disabled = true; subjectSelect.disabled = true;
        sectionSelect.innerHTML = '<option value="">Loading...</option>'; subjectSelect.innerHTML = '<option value="">Loading...</option>';
        fetch('{{ route('admin.academics.syllabus.options', absolute: false) }}?class_id=' + encodeURIComponent(classSelect.value), {headers: {'Accept': 'application/json'}})
            .then(response => response.json())
            .then(data => { fill(sectionSelect, data.sections || [], 'section', selectedSection); fill(subjectSelect, data.subjects || [], 'subject', selectedSubject); })
            .catch(() => { fill(sectionSelect, [], 'section', ''); fill(subjectSelect, [], 'subject', ''); })
            .finally(() => { sectionSelect.disabled = false; subjectSelect.disabled = false; });
    };
    classSelect.addEventListener('change', () => load());
    document.getElementById('syllabus-branch')?.addEventListener('change', () => { if (classSelect.value) load(); });
    if (classSelect.value && (sectionSelect.options.length <= 1 || subjectSelect.options.length <= 1)) load(initialSection, initialSubject);
})();
</script>
@endpush

@push('scripts')
<script>
document.querySelectorAll('#syllabus-results th').forEach((header, index) => header.addEventListener('click', () => {
    const table = header.closest('table'), body = table.tBodies[0], rows = [...body.rows];
    const ascending = header.dataset.order !== 'asc';
    table.querySelectorAll('th').forEach(cell => cell.dataset.order = '');
    header.dataset.order = ascending ? 'asc' : 'desc';
    header.textContent = header.textContent.replace(/[▼▲]/g, '').trim() + (ascending ? ' ▲' : ' ▼');
    rows.sort((a, b) => (a.cells[index]?.innerText || '').localeCompare(b.cells[index]?.innerText || '', undefined, {numeric: true, sensitivity: 'base'}) * (ascending ? 1 : -1));
    rows.forEach(row => body.appendChild(row));
}));
</script>
@endpush
