<?php $__env->startSection('title', 'Profile'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('user.partials.nav', ['moduleKey' => 'profile'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="rounded border border-neutral-200 bg-white p-4">
        <h2 class="mb-4 text-lg font-semibold">Student Profile</h2>

        <?php if($student): ?>
            <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <?php $__currentLoopData = ['admission_no', 'roll_no', 'firstname', 'middlename', 'lastname', 'mobileno', 'email', 'dob', 'gender', 'father_name', 'mother_name', 'guardian_name']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <dt class="text-sm font-medium text-neutral-500"><?php echo e(\Illuminate\Support\Str::headline($column)); ?></dt>
                        <dd class="mt-1 text-neutral-900"><?php echo e(data_get($student, $column) ?: 'Not provided'); ?></dd>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </dl>
        <?php else: ?>
            <p class="text-neutral-600">No linked student profile is available for this authenticated account.</p>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\user\profile\show.blade.php ENDPATH**/ ?>