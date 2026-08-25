<?php $__env->startSection('title', 'Reports'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <?php $__currentLoopData = $reportCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <section class="rounded border border-neutral-200 bg-white p-4">
                <p class="text-sm text-neutral-500"><?php echo e($label); ?></p>
                <p class="mt-2 text-2xl font-semibold"><?php echo e(number_format($value)); ?></p>
            </section>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\reports\index.blade.php ENDPATH**/ ?>