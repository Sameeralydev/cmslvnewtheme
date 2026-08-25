<nav class="mb-4 flex gap-2 overflow-x-auto rounded border border-neutral-200 bg-white p-3 text-sm">
    <a href="<?php echo e(route('admin.academics.dashboard')); ?>" class="shrink-0 rounded px-3 py-2 <?php echo e(request()->routeIs('admin.academics.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-neutral-700 hover:bg-neutral-100'); ?>">Overview</a>
    <?php $__currentLoopData = $modules ?? app(\App\Services\Academics\AcademicModuleRegistry::class)->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route($module['route'])); ?>" class="shrink-0 rounded px-3 py-2 <?php echo e(request()->routeIs($module['route']) ? 'bg-blue-50 text-blue-700' : 'text-neutral-700 hover:bg-neutral-100'); ?>">
            <?php echo e($module['label']); ?>

        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</nav>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\academics\partials\nav.blade.php ENDPATH**/ ?>