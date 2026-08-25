<?php $__env->startSection('title', $module['label']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('user.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form method="GET" action="<?php echo e(route($module['route'])); ?>" class="mb-4 flex gap-2 rounded border border-neutral-200 bg-white p-3">
        <input name="search" value="<?php echo e(request('search')); ?>" class="w-full rounded border border-neutral-300 px-3 py-2" placeholder="Search <?php echo e(strtolower($module['label'])); ?>">
        <button class="rounded bg-blue-600 px-4 py-2 text-white">Search</button>
    </form>

    <section class="overflow-x-auto rounded border border-neutral-200 bg-white">
        <div class="border-b border-neutral-200 px-4 py-3">
            <p class="text-sm text-neutral-500">Legacy table: <?php echo e($module['table']); ?></p>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-neutral-50">
                <tr>
                    <?php $__currentLoopData = $module['columns']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-4 py-3"><?php echo e(\Illuminate\Support\Str::headline($column)); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <?php $__currentLoopData = $module['columns']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td class="max-w-sm px-4 py-3">
                                <?php echo e(\Illuminate\Support\Str::limit(strip_tags((string) data_get($record, $column)), 120)); ?>

                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="<?php echo e(count($module['columns'])); ?>" class="px-4 py-3 text-neutral-600">
                            No <?php echo e(strtolower($module['label'])); ?> records found, or the legacy table is not available in this environment.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <div class="mt-4"><?php echo e($records->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\user\modules\index.blade.php ENDPATH**/ ?>