<?php $__env->startSection('title', $viewMode ? 'View Staff Demand' : ($demand ? 'Edit Staff Demand' : 'Staff Demand')); ?>

<?php
    $editing = $demand !== null && !$viewMode;
    $value = fn ($key, $default = '') => old($key, $demand?->{$key} ?? $default);
    $nature = $demand?->natureOfJob() ?? old('nature_of_job', '');
    $selectedCampus = (string) $value('campus');
    $selectedRequester = (string) $value('requester_name', $demand?->requester_name ?? '');
?>

<?php $__env->startSection('content'); ?>
<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>
<div class="staff-demand-layout items-start gap-3">
    <section class="overflow-hidden rounded-md border border-[#d8dce5] bg-white shadow-sm">
        <div class="border-b border-[#d8dce5] px-4 py-3"><h2 class="text-[16px] font-medium text-[#222]"><?php echo e($viewMode ? 'View Staff Demand' : ($editing ? 'Edit Staff Demand' : 'Add Staff Demand')); ?></h2></div>
        <form method="POST" action="<?php echo e($editing ? route('admin.hrms.staffdemand.update', $demand->id, false) : route('admin.hrms.staffdemand.store', absolute: false)); ?>" class="px-4 py-3">
            <?php echo csrf_field(); ?>
            <?php if($editing): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <?php if(session('success')): ?> <div class="mb-3 border border-green-200 bg-green-50 px-3 py-2 text-[12px] text-green-700"><?php echo e(session('success')); ?></div> <?php endif; ?>
            <?php if($errors->any()): ?> <div class="mb-3 border border-red-200 bg-red-50 px-3 py-2 text-[12px] text-red-700">Please correct the highlighted fields.</div> <?php endif; ?>

            <?php $fields = [
                ['name'=>'staffRequired','label'=>'Staff Required','type'=>'number','required'=>true,'value'=>$value('staffRequired', $demand?->staff_required ?? ''), 'attr'=>'min="1"'],
                ['name'=>'demandDate','label'=>'Demand Date','type'=>'date','required'=>true,'value'=>$value('demandDate', $demand?->demand_date?->format('Y-m-d') ?? $today)],
            ]; ?>
            <div class="mb-3"><label class="label">Branch <span>*</span></label><select name="campus" id="campus_select" class="input" <?php echo e($viewMode ? 'disabled' : 'required'); ?>><option value="">Select Campus</option><?php $__currentLoopData = $campuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($campus->id); ?>" <?php if($selectedCampus === (string)$campus->id): echo 'selected'; endif; ?>><?php echo e($campus->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['campus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="error"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="mb-3"><label class="label">Requester Name <span>*</span></label><?php if($viewMode): ?><input class="input" readonly value="<?php echo e(trim(($demand->staff_name ?? '') . ' ' . ($demand->staff_surname ?? ''))); ?> (<?php echo e($demand->employee_id ?? ''); ?>)"><?php else: ?><select name="requesterName" id="requester_select" class="input" required><option value="">Select Campus First</option></select><?php endif; ?> <?php $__errorArgs = ['requesterName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="error"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="mb-3"><label class="label"><?php echo e($field['label']); ?> <span>*</span></label><input name="<?php echo e($field['name']); ?>" type="<?php echo e($field['type']); ?>" value="<?php echo e($field['value']); ?>" class="input" <?php echo e($field['attr'] ?? ''); ?> <?php echo e($viewMode ? 'readonly' : ($field['required'] ? 'required' : '')); ?>><?php $__errorArgs = [$field['name']];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="error"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-3"><label class="label">Position <span>*</span></label><?php if($viewMode): ?><input class="input" readonly value="<?php echo e($demand->position ?? ''); ?>"><?php else: ?><select name="position" class="input" required><option value="">Select Position</option><?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option <?php if($value('position') === $item): echo 'selected'; endif; ?>><?php echo e($item); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php endif; ?></div>
            <div class="mb-3"><label class="label">Nature of Job <span>*</span></label><?php if($viewMode): ?><input class="input" readonly value="<?php echo e($natures[$nature] ?? ''); ?>"><?php else: ?><select name="nature_of_job" class="input" required><option value="">Select Nature of Job</option><?php $__currentLoopData = $natures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>" <?php if($nature === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php endif; ?></div>
            <div class="mb-3"><label class="label">Academic Qualifications <span>*</span></label><textarea name="academicQualifications" class="input" rows="3" <?php echo e($viewMode ? 'readonly' : 'required'); ?>><?php echo e($value('academicQualifications', $demand?->academic_qualifications ?? '')); ?></textarea></div>
            <div class="mb-3"><label class="label">Professional Qualifications</label><textarea name="professionalQualifications" class="input" rows="3" <?php echo e($viewMode ? 'readonly' : ''); ?>><?php echo e($value('professionalQualifications', $demand?->professional_qualifications ?? '')); ?></textarea></div>
            <div class="mb-3"><label class="label">Role <span>*</span></label><?php if($viewMode): ?><input class="input" readonly value="<?php echo e($demand->role ?? ''); ?>"><?php else: ?><select name="role" class="input" required><option value="">Select Role</option><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option <?php if($value('role') === $item): echo 'selected'; endif; ?>><?php echo e($item); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php endif; ?></div>
            <?php $__currentLoopData = [['experience','Experience',true],['expectedSkills','Expected Skills',false],['expectedAttitude','Expected Attitude',false],['ageRange','Age Range',false],['salaryRange','Salary Range',false]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$name,$label,$required]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="mb-3"><label class="label"><?php echo e($label); ?> <?php if($required): ?><span>*</span><?php endif; ?></label><textarea name="<?php echo e($name); ?>" class="input" rows="2" <?php echo e($viewMode ? 'readonly' : ''); ?> <?php echo e($required && !$viewMode ? 'required' : ''); ?>><?php echo e($value($name, $demand?->{strtolower(preg_replace('/([A-Z])/', '_$1', $name))} ?? '')); ?></textarea></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if(!$viewMode): ?><div class="flex justify-end gap-2 pt-2"><a href="<?php echo e(route('admin.hrms.staffdemand.index', absolute: false)); ?>" class="button secondary"><?php echo e($editing ? 'Cancel' : 'Reset'); ?></a><button class="button primary" type="submit"><?php echo e($editing ? 'Update' : 'Save'); ?></button></div><?php else: ?><div class="flex justify-end gap-2 pt-2"><a href="<?php echo e(route('admin.hrms.staffdemand.index', absolute: false)); ?>" class="button secondary">Back to List</a><a href="<?php echo e(route('admin.hrms.staffdemand.edit', $demand->id, false)); ?>" class="button primary">Edit</a></div><?php endif; ?>
        </form>
    </section>

    <section class="staff-demand-records overflow-hidden rounded-md border border-[#d8dce5] bg-white shadow-sm"><div class="border-b border-[#d8dce5] px-4 py-3"><h2 class="text-[16px] font-medium text-[#222]">Staff Demand List</h2></div><div class="demand-toolbar"><input id="demand_table_search" type="search" placeholder="Search..." class="demand-search"><div class="demand-tools"><button type="button" title="Copy"><i class="fa fa-copy"></i></button><button type="button" title="Excel"><i class="fa fa-file-excel-o"></i></button><button type="button" title="CSV"><i class="fa fa-file-text-o"></i></button><button type="button" title="PDF"><i class="fa fa-file-pdf-o"></i></button><button type="button" title="Print" onclick="window.print()"><i class="fa fa-print"></i></button><button type="button" title="Columns"><i class="fa fa-columns"></i></button></div></div><div class="overflow-x-auto p-3"><table id="demand_table" class="demand-table min-w-full border-collapse text-left text-[11px]"><thead class="bg-[#626262] text-white"><tr><th class="demand-head">ID <i class="fa fa-caret-down"></i></th><th class="demand-head">Campus <i class="fa fa-caret-down"></i></th><th class="demand-head">Requester <i class="fa fa-caret-down"></i></th><th class="demand-head">Position <i class="fa fa-caret-down"></i></th><th class="demand-head">Staff Required <i class="fa fa-caret-down"></i></th><th class="demand-head">Date <i class="fa fa-caret-down"></i></th><th class="demand-head">Action</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $demands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr class="demand-row"><td class="demand-cell"><?php echo e($loop->iteration); ?></td><td class="demand-cell"><?php echo e($item->campus_name); ?></td><td class="demand-cell"><span class="demand-popover" title="Details" data-detail="Designation: <?php echo e($item->designation); ?> | Department: <?php echo e($item->department); ?> | Nature: <?php echo e($item->natureOfJob()); ?>"><?php echo e(trim(($item->staff_name ?? '') . ' ' . ($item->staff_surname ?? ''))); ?> (<?php echo e($item->employee_id ?? ''); ?>)</span></td><td class="demand-cell"><?php echo e($item->position); ?></td><td class="demand-cell"><?php echo e($item->staff_required); ?></td><td class="demand-cell"><?php echo e($item->demand_date?->format('Y-m-d')); ?></td><td class="demand-cell whitespace-nowrap"><a class="action view" title="View" href="<?php echo e(route('admin.hrms.staffdemand.show', $item->id, false)); ?>"><i class="fa fa-eye"></i></a><a class="action edit" title="Edit" href="<?php echo e(route('admin.hrms.staffdemand.edit', $item->id, false)); ?>"><i class="fa fa-pencil"></i></a><form class="inline" method="POST" action="<?php echo e(route('admin.hrms.staffdemand.destroy', $item->id, false)); ?>" onsubmit="return confirm('Are you sure you want to delete this staff demand?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="action delete" title="Delete"><i class="fa fa-times"></i></button></form></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="7" class="demand-cell py-4 text-center">No staff demands found</td></tr><?php endif; ?></tbody></table><div class="demand-record-count">Records: <?php echo e($demands->count() ? 1 : 0); ?> to <?php echo e($demands->count()); ?> of <?php echo e($demands->count()); ?></div></div></section>
</div>
<?php $__env->startPush('styles'); ?><style>.staff-demand-layout{display:grid;grid-template-columns:minmax(360px,1fr) minmax(0,2fr);align-items:start;gap:12px}.label{display:block;margin-bottom:5px;font-size:13px;font-weight:600;color:#222}.label span{color:#e11d48}.input{width:100%;border:1px solid #cfd6e0;padding:7px 10px;font-size:13px;color:#333;outline:0}.input:focus{border-color:#264796}.error{display:block;color:#dc2626;font-size:11px;margin-top:3px}.button{display:inline-block;padding:7px 14px;font-size:12px;font-weight:600}.button.primary{background:#264796;color:#fff}.button.secondary{border:1px solid #cfd6e0;color:#333}.action{display:inline-flex;width:24px;height:24px;align-items:center;justify-content:center;margin-right:3px;color:#fff;font-weight:bold}.action.view{background:#17a2b8}.action.edit{background:#264796}.action.delete{border:0;background:#dc3545;cursor:pointer}.demand-popover{cursor:help;border-bottom:1px dotted #264796}@media (max-width:900px){.staff-demand-layout{grid-template-columns:1fr}}</style><?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?><script>document.addEventListener('DOMContentLoaded',()=>{const campus=document.getElementById('campus_select'), requester=document.getElementById('requester_select');if(!campus||!requester)return;const selected=<?php echo json_encode($selectedRequester, 15, 512) ?>;const load=()=>{requester.innerHTML='<option value="">Loading...</option>';if(!campus.value){requester.innerHTML='<option value="">Select Campus First</option>';return}fetch(<?php echo json_encode(route('admin.hrms.staffdemand.staff-by-campus', absolute: false), 512) ?>,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Accept':'application/json'},body:JSON.stringify({campus_id:campus.value})}).then(r=>r.json()).then(rows=>{requester.innerHTML='<option value="">Select Requester</option>';rows.forEach(staff=>{const o=new Option(`${staff.name} ${staff.surname||''} (${staff.employee_id||''})`,staff.id);o.selected=String(staff.id)===selected;requester.add(o)})}).catch(()=>requester.innerHTML='<option value="">Error loading staff</option>')};campus.addEventListener('change',load);if(campus.value)load()});</script><?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\hrms\staffdemand\index.blade.php ENDPATH**/ ?>