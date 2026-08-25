<?php $userRegistry = app('App\Services\User\UserModuleRegistry'); ?>

<nav class="mb-4 flex flex-wrap gap-2">
    <a href="<?php echo e(route('user.dashboard')); ?>" class="rounded border px-3 py-2 text-sm <?php echo e(($moduleKey ?? null) === 'dashboard' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700'); ?>">Dashboard</a>
    <a href="<?php echo e(route('user.profile.show')); ?>" class="rounded border px-3 py-2 text-sm <?php echo e(($moduleKey ?? null) === 'profile' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700'); ?>">Profile</a>
    <a href="<?php echo e(route('user.fees.index')); ?>" class="rounded border px-3 py-2 text-sm <?php echo e(($moduleKey ?? null) === 'fees' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700'); ?>">Fees</a>

    <?php $__currentLoopData = $userRegistry->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $registeredModule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($key === 'dashboard') continue; ?>

        <a
            href="<?php echo e(route($registeredModule['route'])); ?>"
            class="rounded border px-3 py-2 text-sm <?php echo e(($moduleKey ?? null) === $key ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700'); ?>"
        >
            <?php echo e($registeredModule['label']); ?>

        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <a href="<?php echo e(route('user.password.edit')); ?>" class="rounded border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700">Password</a>
    <a href="<?php echo e(route('user.username.edit')); ?>" class="rounded border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700">Username</a>
</nav>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\user\partials\nav.blade.php ENDPATH**/ ?>