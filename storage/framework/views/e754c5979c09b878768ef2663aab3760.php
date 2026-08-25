<section class="mb-4">
    <h1 class="text-2xl font-semibold text-neutral-900">ADM / Student Affairs</h1>
    <div class="mt-3 flex flex-wrap gap-2 text-sm">
        <?php $__currentLoopData = app(\App\Services\Adm\AdmModuleRegistry::class)->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route($item['route'])); ?>" class="rounded border px-3 py-2 <?php echo e(request()->routeIs($item['route']) ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-50'); ?>">
                <?php echo e($item['label']); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\adm\partials\nav.blade.php ENDPATH**/ ?>