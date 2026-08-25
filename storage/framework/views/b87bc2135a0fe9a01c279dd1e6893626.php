<?php $teacherRegistry = app('App\Services\Teacher\TeacherModuleRegistry'); ?>
<?php $teacherContext = app('App\Services\Teacher\TeacherContext'); ?>

<nav class="mb-4 flex flex-wrap gap-2">
    <?php $__currentLoopData = $teacherRegistry->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $registeredModule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($key === 'profile') continue; ?>

        <a
            href="<?php echo e(route($registeredModule['route'])); ?>"
            class="rounded border px-3 py-2 text-sm <?php echo e(($moduleKey ?? null) === $key ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700'); ?>"
        >
            <?php echo e($registeredModule['label']); ?>

        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <a
        href="<?php echo e(route('teacher.profile.show', $teacherContext->staffId() ?? auth()->id() ?? 0)); ?>"
        class="rounded border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700"
    >
        Profile
    </a>

    <a
        href="<?php echo e(route('teacher.password.edit')); ?>"
        class="rounded border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700"
    >
        Change Password
    </a>
</nav>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\teacher\partials\nav.blade.php ENDPATH**/ ?>