<?php $__env->startSection('title', 'QMS'); ?>

<?php $__env->startSection('content'); ?>
    <section class="rounded border border-amber-200 bg-amber-50 p-4 text-amber-900">
        <h2 class="font-semibold">Migration note</h2>
        <p class="mt-2 text-sm"><?php echo e($migrationNote); ?></p>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\qms\index.blade.php ENDPATH**/ ?>