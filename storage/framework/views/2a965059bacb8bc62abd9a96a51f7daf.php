<?php $__env->startSection('title', 'Student Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('user.partials.nav', ['moduleKey' => 'dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="grid gap-4 md:grid-cols-3">
        <section class="rounded border border-neutral-200 bg-white p-4">
            <h2 class="mb-3 text-lg font-semibold">Selected Student</h2>
            <p class="text-sm text-neutral-700"><?php echo e($student?->firstname); ?> <?php echo e($student?->lastname); ?></p>
            <p class="text-sm text-neutral-500">Admission: <?php echo e($student?->admission_no ?: 'Not available'); ?></p>
            <p class="text-sm text-neutral-500">Roll: <?php echo e($student?->roll_no ?: 'Not available'); ?></p>
        </section>

        <section class="rounded border border-neutral-200 bg-white p-4">
            <h2 class="mb-3 text-lg font-semibold">Class Session</h2>
            <p class="text-sm text-neutral-500">Class ID: <?php echo e($studentSession?->class_id ?: 'Not available'); ?></p>
            <p class="text-sm text-neutral-500">Section ID: <?php echo e($studentSession?->section_id ?: 'Not available'); ?></p>
            <p class="text-sm text-neutral-500">Session ID: <?php echo e($studentSession?->session_id ?: 'Not available'); ?></p>
        </section>

        <section class="rounded border border-neutral-200 bg-white p-4">
            <h2 class="mb-3 text-lg font-semibold">Fee Summary</h2>
            <p class="text-sm text-neutral-500">Assigned: <?php echo e(number_format($feeSummary['totals']['assigned_amount'], 2)); ?></p>
            <p class="text-sm text-neutral-500">Paid: <?php echo e(number_format($feeSummary['totals']['paid_amount'], 2)); ?></p>
            <p class="text-sm font-medium text-neutral-900">Balance: <?php echo e(number_format($feeSummary['totals']['balance'], 2)); ?></p>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\user\dashboard\index.blade.php ENDPATH**/ ?>