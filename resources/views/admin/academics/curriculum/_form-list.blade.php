@php
    $edit = $subject ?? $subjectGroup ?? $chapter ?? $topic ?? $module ?? $domain ?? null;
    $titles = ['subjects'=>'Subject','subject-groups'=>'Subject Group','chapters'=>'Chapter','topics'=>'Topic','domain-modules'=>'Modules','domains'=>'Domain'];
    $title = $titles[$kind];
    $routeBase = ['subjects'=>'subjects','subject-groups'=>'subject-groups','chapters'=>'chapters','topics'=>'topics','domain-modules'=>'domain-modules','domains'=>'domains'][$kind];
@endphp
@push('styles')
<style>
#curriculum-table { border:1px solid #d1d5db; border-collapse:separate; border-spacing:0; }
#curriculum-table th, #curriculum-table td { border-right:1px solid #d1d5db; border-bottom:1px solid #d1d5db; }
#curriculum-table th:last-child, #curriculum-table td:last-child { border-right:0; }
#curriculum-table tbody tr:last-child td { border-bottom:0; }
#curriculum-table th.cursor-pointer { cursor:pointer; user-select:none; }
.curriculum-actions { justify-content:center; gap:4px; }
.curriculum-actions a, .curriculum-actions button { width:20px; height:20px; min-width:20px; padding:0; border-radius:2px; line-height:20px; box-shadow:none; }
.curriculum-actions a { background:#203f8f; }
.curriculum-actions button { background:#e53935; }
.curriculum-actions a:hover { background:#183477; }
.curriculum-actions button:hover { background:#c62828; }
@media print {
    .admin-sidebar,.admin-topbar,.main-footer,.curriculum-print-hide,.curriculum-actions { display:none!important; }
    .admin-main > .mb-4 { display:none!important; }
    .admin-content-wrap { margin:0!important; padding:0!important; }
    .admin-main { padding:0!important; }
    #curriculum-table { width:100%!important; border-collapse:collapse!important; }
    #curriculum-table th { background:#1e3a8a!important; color:#fff!important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
}
</style>
@endpush
@push('scripts')
<script>(function(){const headers=[...document.querySelectorAll('#curriculum-table th.cursor-pointer')];headers.forEach(h=>{h.dataset.asc='1';h.textContent=h.textContent.replace(/[\u2195\u2191\u2193]|â†•/g,'').trim()+' ↑';h.addEventListener('click',()=>{const up=h.dataset.asc==='1';h.textContent=h.textContent.replace(/[\u2195\u2191\u2193]|â†•/g,'').trim()+(up?' ↓':' ↑')})})})();</script>
@endpush
<div class="grid gap-4 lg:grid-cols-3">
    <section class="curriculum-print-hide rounded border border-neutral-200 bg-white p-4 lg:col-span-1">
        <h2 class="mb-4 border-b border-neutral-200 pb-3 text-lg font-medium">{{ $edit ? 'Edit '.$title : 'Add '.$title }}</h2>
        <form method="POST" action="{{ $edit ? route('admin.academics.'.$routeBase.'.update',$edit) : route('admin.academics.'.$routeBase.'.store') }}" class="space-y-4">
            @csrf @if($edit) @method('PUT') @endif
            @if($kind==='subjects')
                <div><label class="mb-1 block text-sm">Subject Name <span class="text-red-600">*</span></label><input name="name" value="{{ old('name',$subject?->name) }}" required class="w-full rounded border border-neutral-300 px-3 py-2">@error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror</div>
                <div class="flex gap-4"><label><input type="radio" name="type" value="theory" @checked(old('type',$subject?->type)==='theory') required> Theory</label><label><input type="radio" name="type" value="practical" @checked(old('type',$subject?->type)==='practical')> Practical</label></div>
                <div><label class="mb-1 block text-sm">Subject Code <span class="text-red-600">*</span></label><input name="code" value="{{ old('code',$subject?->code) }}" required class="w-full rounded border border-neutral-300 px-3 py-2">@error('code')<p class="text-sm text-red-600">{{ $message }}</p>@enderror</div>
            @elseif($kind==='subject-groups')
                <div><label class="mb-1 block text-sm">Class <span class="text-red-600">*</span></label><select name="class_id" required class="w-full rounded border border-neutral-300 px-3 py-2"><option value="">Select</option>@foreach($classes as $c)<option value="{{ $c->id }}" @selected((string)old('class_id',$subjectGroup?->class_id)===(string)$c->id)>{{ $c->class }}</option>@endforeach</select>@error('class_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror</div>
                <div><label class="mb-1 block text-sm">Name <span class="text-red-600">*</span></label><input name="name" value="{{ old('name',$subjectGroup?->name) }}" required class="w-full rounded border border-neutral-300 px-3 py-2">@error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror</div>
                <div><label class="mb-1 block text-sm">Subjects <span class="text-red-600">*</span></label>@foreach($subjects as $s)<label class="block py-1 text-sm"><input type="checkbox" name="subjects[]" value="{{ $s->id }}" @checked(in_array($s->id,old('subjects',$subjectGroup?->subjects?->pluck('id')->all()??[])))> {{ $s->name }} ({{ $s->code }})</label>@endforeach</div>
                <div><label class="mb-1 block text-sm">Description</label><textarea name="description" rows="3" class="w-full rounded border border-neutral-300 px-3 py-2">{{ old('description',$subjectGroup?->description) }}</textarea></div>
            @elseif($kind==='chapters' || $kind==='topics')
                <div><label class="mb-1 block text-sm">Class <span class="text-red-600">*</span></label><select id="curriculum-class" name="class_id" required class="w-full rounded border border-neutral-300 px-3 py-2"><option value="">Select</option>@foreach($classes as $c)<option value="{{ $c->id }}" @selected((string)old('class_id',$selectedClass)===(string)$c->id)>{{ $c->class }}</option>@endforeach</select></div>
                <div><label class="mb-1 block text-sm">Subject <span class="text-red-600">*</span></label><select id="curriculum-subject" name="subject_id" required class="w-full rounded border border-neutral-300 px-3 py-2"><option value="">Select</option>@foreach($subjects as $s)<option value="{{ $s->id }}" @selected((string)old('subject_id',$topic?->subject_id??$chapter?->subject_id)===(string)$s->id)>{{ $s->name }} ({{ $s->code }})</option>@endforeach</select></div>
                @if($kind==='topics')<div><label class="mb-1 block text-sm">Chapter <span class="text-red-600">*</span></label><select name="chapter_id" required class="w-full rounded border border-neutral-300 px-3 py-2"><option value="">Select</option>@foreach($chapters as $co)<option value="{{ $co->id }}" @selected((string)old('chapter_id',$topic?->chapter_id)===(string)$co->id)>{{ $co->name }} @if($co->urdu) / {{ $co->urdu }} @endif</option>@endforeach</select></div>@endif
                <div id="name-rows" class="space-y-3">@php($eng=old('eng_name',$edit?[$edit->name]:[''])) @php($urd=old('urdu_name',$edit?[$edit->urdu]:[''])) @foreach($eng as $i=>$v)<div class="grid grid-cols-2 gap-2"><div><label class="mb-1 block text-sm">Name English {{ $i===0?'*':'' }}</label><input name="eng_name[]" value="{{ $v }}" required class="w-full rounded border border-neutral-300 px-3 py-2"></div><div><label class="mb-1 block text-sm">Name Urdu</label><input name="urdu_name[]" value="{{ $urd[$i]??'' }}" class="w-full rounded border border-neutral-300 px-3 py-2"></div></div>@endforeach</div><button type="button" id="add-name" class="rounded bg-blue-600 px-3 py-1 text-sm text-white">+ Add More</button>
            @elseif($kind==='domain-modules')
                <div id="module-rows" class="space-y-3">@php($eng=old('eng_name',$module?[$module->name]:[''])) @php($urd=old('urdu_name',$module?[$module->urdu]:[''])) @foreach($eng as $i=>$v)<div class="grid grid-cols-2 gap-2"><input name="eng_name[]" value="{{ $v }}" placeholder="Name English *" required class="rounded border border-neutral-300 px-3 py-2"><input name="urdu_name[]" value="{{ $urd[$i]??'' }}" placeholder="Name Urdu" class="rounded border border-neutral-300 px-3 py-2"></div>@endforeach</div><button type="button" id="add-name" class="rounded bg-blue-600 px-3 py-1 text-sm text-white">+ Add More</button>
            @else
                <div><label class="mb-1 block text-sm">Subject <span class="text-red-600">*</span></label><select name="subject_id" required class="w-full rounded border border-neutral-300 px-3 py-2"><option value="">Select</option>@foreach($subjects as $s)<option value="{{ $s->id }}" @selected((string)old('subject_id',$domain?->subject_id)===(string)$s->id)>{{ $s->name }}</option>@endforeach</select></div>
                <div><label class="mb-1 block text-sm">Modules <span class="text-red-600">*</span></label>@foreach($modules as $m)<label class="block py-1 text-sm"><input type="checkbox" name="modules[]" value="{{ $m->id }}" @checked(in_array($m->id,old('modules',$selectedModules)))> {{ $m->name }}</label>@endforeach</div>
            @endif
            <div class="flex justify-end gap-2 border-t border-neutral-200 pt-3">@if($edit)<a href="{{ route('admin.academics.'.$routeBase.'.index') }}" class="rounded border border-neutral-300 px-4 py-2">Cancel</a>@endif<button class="rounded bg-blue-600 px-4 py-2 text-white">{{ $edit?'Update':'Save' }}</button></div>
        </form>
    </section>
    <section class="rounded border border-neutral-200 bg-white p-4 lg:col-span-2">
        <div class="curriculum-print-hide mb-3 flex flex-wrap items-center justify-between gap-2"><h2 class="text-lg font-medium">{{ $title }} List</h2><div class="flex flex-wrap items-center gap-1"><form><input name="search" value="{{ request('search') }}" placeholder="Search..." class="rounded border border-neutral-300 px-3 py-2"></form><button type="button" data-export="copy" title="Copy" aria-label="Copy" class="h-8 w-8 rounded bg-blue-800 text-white hover:bg-blue-700"><i class="fa-regular fa-copy"></i></button><button type="button" data-export="excel" title="Excel" aria-label="Excel" class="h-8 w-8 rounded bg-blue-800 text-white hover:bg-blue-700"><i class="fa-regular fa-file-excel"></i></button><button type="button" data-export="csv" title="CSV" aria-label="CSV" class="h-8 w-8 rounded bg-blue-800 text-white hover:bg-blue-700"><i class="fa-solid fa-file-csv"></i></button><button type="button" data-export="pdf" title="PDF" aria-label="PDF" class="h-8 w-8 rounded bg-blue-800 text-white hover:bg-blue-700"><i class="fa-regular fa-file-pdf"></i></button><button type="button" data-export="print" title="Print" aria-label="Print" class="h-8 w-8 rounded bg-blue-800 text-white hover:bg-blue-700"><i class="fa-solid fa-print"></i></button><button type="button" id="column-toggle" title="Columns" aria-label="Columns" class="h-8 w-8 rounded bg-blue-800 text-white hover:bg-blue-700"><i class="fa-solid fa-table-columns"></i></button></div></div>
        <div class="overflow-x-auto"><table id="curriculum-table" class="w-full text-left text-sm"><thead class="bg-blue-800 text-white"><tr>@if($kind==='subjects')<th class="cursor-pointer px-3 py-2">Subject ↕</th><th class="cursor-pointer px-3 py-2">Subject Code ↕</th><th class="cursor-pointer px-3 py-2">Subject Type ↕</th><th class="px-3 py-2">Action</th>@elseif($kind==='subject-groups')<th class="cursor-pointer px-3 py-2">Name ↕</th><th class="cursor-pointer px-3 py-2">Class ↕</th><th class="cursor-pointer px-3 py-2">Subject ↕</th><th class="px-3 py-2">Action</th>@elseif($kind==='chapters')<th class="cursor-pointer px-3 py-2">Class ↕</th><th class="px-3 py-2">Subject / Chapter</th>@elseif($kind==='topics')<th class="cursor-pointer px-3 py-2">Class ↕</th><th class="px-3 py-2">Subject / Chapter / Topic</th>@elseif($kind==='domain-modules')<th class="cursor-pointer px-3 py-2">English Name ↕</th><th class="cursor-pointer px-3 py-2">Urdu Name ↕</th><th class="px-3 py-2">Action</th>@else<th class="cursor-pointer px-3 py-2">Subject ↕</th><th class="cursor-pointer px-3 py-2">Domain ↕</th><th class="px-3 py-2">Action</th>@endif</tr></thead><tbody class="divide-y divide-neutral-200">
        @forelse($records as $r)
            @if($kind==='subjects')<tr><td class="px-3 py-2">{{ $r->name }}</td><td class="px-3 py-2">{{ $r->code }}</td><td class="px-3 py-2">{{ ucfirst($r->type) }}</td><td class="px-3 py-2">@include('admin.academics.curriculum._actions',['routeBase'=>'subjects','record'=>$r])</td></tr>
            @elseif($kind==='subject-groups')<tr><td class="px-3 py-2">{{ $r->name }}</td><td class="px-3 py-2">{{ $classNames[$r->class_id]??$r->class_id }}</td><td class="px-3 py-2">@foreach($r->subjects as $s)<div>{{ $s->name }}</div>@endforeach</td><td class="px-3 py-2">@include('admin.academics.curriculum._actions',['routeBase'=>'subject-groups','record'=>$r])</td></tr>
            @elseif($kind==='chapters')<tr><td class="px-3 py-2">{{ $classNames[$r->class_id]??$r->class_id }}</td><td class="px-3 py-2"><div>{{ $r->subject?->name }}</div>{{ $r->name }} / {{ $r->urdu }} <span class="float-right">@include('admin.academics.curriculum._actions',['routeBase'=>'chapters','record'=>$r])</span></td></tr>
            @elseif($kind==='topics')<tr><td class="px-3 py-2">{{ $classNames[$r->class_id]??$r->class_id }}</td><td class="px-3 py-2"><div>{{ $r->chapter?->subject?->name }}</div><div>{{ $r->chapter?->name }}</div>{{ $r->name }} / {{ $r->urdu }} <span class="float-right">@include('admin.academics.curriculum._actions',['routeBase'=>'topics','record'=>$r])</span></td></tr>
            @elseif($kind==='domain-modules')<tr><td class="px-3 py-2">{{ $r->name }}</td><td class="px-3 py-2" dir="rtl">{{ $r->urdu }}</td><td class="px-3 py-2">@include('admin.academics.curriculum._actions',['routeBase'=>'domain-modules','record'=>$r])</td></tr>
            @else<tr><td class="px-3 py-2">{{ $r->subject_name }}</td><td class="px-3 py-2">@foreach($r->module_names as $n)<div>{{ $n }}</div>@endforeach</td><td class="px-3 py-2">@include('admin.academics.curriculum._actions',['routeBase'=>'domains','record'=>$r])</td></tr>@endif
        @empty<tr><td colspan="4" class="px-3 py-8 text-center text-neutral-500">No data available</td></tr>@endforelse
        </tbody></table></div><div class="mt-3">{{ $records->links() }}</div>
    </section>
</div>
@push('scripts')
<script>
document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-export="pdf"], [data-export="print"]');
    if (!button) return;
    const headers = [...document.querySelectorAll('#curriculum-table th.cursor-pointer')];
    const original = headers.map(header => header.textContent);
    const table = document.querySelector('#curriculum-table');
    const actionIndex = [...table.tHead.rows[0].cells].findIndex(cell => cell.textContent.trim().toLowerCase() === 'action');
    const actionCells = actionIndex >= 0 ? [...table.rows].map(row => row.cells[actionIndex]).filter(Boolean) : [];
    headers.forEach(header => {
        header.textContent = header.textContent.replace(/[\u2191\u2193\u2195]/g, '').trim();
    });
    if (actionIndex >= 0) {
        table.tHead.rows[0].cells[actionIndex].style.display = 'none';
        actionCells.forEach(cell => cell.style.display = 'none');
    }
    window.setTimeout(() => {
        headers.forEach((header, index) => header.textContent = original[index]);
        if (actionIndex >= 0) {
            table.tHead.rows[0].cells[actionIndex].style.display = '';
            actionCells.forEach(cell => cell.style.display = '');
        }
    }, 1000);
}, true);
</script>
@endpush
@push('scripts')<script>document.querySelectorAll('#curriculum-table th.cursor-pointer').forEach((h,i)=>h.addEventListener('click',()=>{const t=h.closest('table'),body=t.tBodies[0],rows=[...body.rows];const asc=h.dataset.asc!=='1';h.dataset.asc=asc?'1':'0';rows.sort((a,b)=>(a.cells[i]?.innerText||'').localeCompare(b.cells[i]?.innerText||'',undefined,{numeric:true,sensitivity:'base'})*(asc?1:-1));rows.forEach(r=>body.appendChild(r))}));document.querySelectorAll('[data-export]').forEach(b=>b.addEventListener('click',async e=>{e.preventDefault();const table=document.getElementById('curriculum-table'),rows=[...table.rows].map(r=>[...r.cells].filter((_,i)=>table.querySelectorAll('th')[i]?.style.display!=='none').map(c=>c.innerText.trim()).join('\t')).join('\n');if(b.dataset.export==='copy')await navigator.clipboard.writeText(rows);if(['csv','excel'].includes(b.dataset.export)){const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([rows],{type:b.dataset.export==='csv'?'text/csv':'application/vnd.ms-excel'}));a.download='curriculum.'+(b.dataset.export==='csv'?'csv':'xls');a.click()}if(b.dataset.export==='pdf'||b.dataset.export==='print')window.print()}));document.getElementById('column-toggle')?.addEventListener('click',()=>{const table=document.getElementById('curriculum-table'),headers=[...table.tHead.rows[0].cells],hidden=headers.map(h=>h.style.display==='none'),menu=document.createElement('div');menu.className='absolute z-50 mt-2 rounded border border-neutral-200 bg-white p-2 text-sm text-neutral-800 shadow-lg';menu.innerHTML=headers.map((h,i)=>`<label class="block whitespace-nowrap px-2 py-1"><input type="checkbox" ${hidden[i]?'':'checked'} data-column="${i}"> ${h.innerText.replace(/[↕]/g,'')}</label>`).join('');const button=document.getElementById('column-toggle');button.parentElement.classList.add('relative');button.parentElement.appendChild(menu);menu.addEventListener('change',e=>{const i=Number(e.target.dataset.column);[...table.rows].forEach(r=>{if(r.cells[i])r.cells[i].style.display=e.target.checked?'':'none'});});menu.addEventListener('mouseleave',()=>menu.remove())});const add=document.getElementById('add-name');add?.addEventListener('click',()=>{const row=document.createElement('div');row.className='grid grid-cols-2 gap-2';row.innerHTML='<input name="eng_name[]" placeholder="Name English *" required class="rounded border border-neutral-300 px-3 py-2"><input name="urdu_name[]" placeholder="Name Urdu" class="rounded border border-neutral-300 px-3 py-2">';document.querySelector('#name-rows, #module-rows')?.appendChild(row)});document.getElementById('curriculum-class')?.addEventListener('change',e=>{const u=new URL(window.location);u.searchParams.set('class_id',e.target.value);u.searchParams.delete('subject_id');window.location=u});document.getElementById('curriculum-subject')?.addEventListener('change',e=>{const u=new URL(window.location);u.searchParams.set('subject_id',e.target.value);window.location=u});</script>@endpush
