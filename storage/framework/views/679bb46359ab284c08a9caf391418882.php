<?php $__env->startSection('title', 'Teacher Profile'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('teacher.partials.nav', ['moduleKey' => 'profile'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="rounded border border-neutral-200 bg-white p-4">
        <p class="mb-4 text-sm text-neutral-500">Legacy table: <?php echo e($module['table']); ?></p>

        <?php if($teacher): ?>
            <dl class="grid gap-4 sm:grid-cols-2">
                <?php $__currentLoopData = $module['columns']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <dt class="text-sm font-medium text-neutral-500"><?php echo e(\Illuminate\Support\Str::headline($column)); ?></dt>
                        <dd class="mt-1 text-neutral-900"><?php echo e(data_get($teacher, $column) ?: 'Not provided'); ?></dd>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </dl>
        <?php else: ?>
            <p class="text-neutral-600">
                Teacher profile #<?php echo e($id); ?> could not be loaded because the legacy staff table is not available in this environment.
            </p>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\teacher\profile\show.blade.php ENDPATH**/ ?>