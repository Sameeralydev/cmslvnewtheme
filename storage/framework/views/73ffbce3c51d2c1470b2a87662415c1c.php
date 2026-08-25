<?php $__env->startSection('title', 'Import Staff'); ?>
<?php $__env->startSection('content'); ?>
<section class="mx-auto max-w-5xl rounded-md border border-[#d8dce5] bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-[#d8dce5] px-4 py-3"><h2 class="text-[14px] font-medium">Staff Import</h2><a href="<?php echo e(route('admin.hrms.staff.index', ['brc_id' => $selectedBranchId], false)); ?>" class="text-[11px] text-[#264796]">Back to Staff Directory</a></div>
    <div class="p-4 text-[11px] text-[#475467]"><p class="mb-3">CSV headers can include: <code>employee_id, name, surname, father_name, email, gender, dob, date_of_joining, contact_no, emergency_contact_no, marital_status, local_address, permanent_address, note</code>.</p>
        <?php if(session('success')): ?> <div class="mb-3 border border-green-200 bg-green-50 px-3 py-2 text-green-700"><?php echo e(session('success')); ?></div> <?php endif; ?>
        <form method="POST" action="<?php echo e(route('admin.hrms.staff.import', absolute: false)); ?>" enctype="multipart/form-data" class="grid gap-3 md:grid-cols-3"><?php echo csrf_field(); ?>
            <div><label class="mb-1 block font-semibold">Branch *</label><select name="brc_id" class="h-9 w-full border px-2" required><?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($branch->id); ?>" <?php if($selectedBranchId === (int)$branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
            <div><label class="mb-1 block font-semibold">Role *</label><select name="role_id" class="h-9 w-full border px-2" required><option value="">Select</option><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($role['id']); ?>"><?php echo e($role['name']); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
            <div><label class="mb-1 block font-semibold">Select CSV File *</label><input type="file" name="file" accept=".csv,text/csv" class="h-9 w-full border px-2" required></div>
            <div class="md:col-span-3"><button class="bg-[#264796] px-4 py-2 font-semibold text-white">Import Staff</button></div>
        </form>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\hrms\staff\import.blade.php ENDPATH**/ ?>