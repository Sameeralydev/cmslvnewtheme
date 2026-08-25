<?php $__env->startSection('title', 'System Settings'); ?>

<?php $__env->startSection('content'); ?>
    <section class="admin-dashboard-section overflow-hidden rounded border border-neutral-300 bg-white shadow-sm">
        <div class="admin-module-tabs flex flex-wrap gap-4 border-b border-amber-300 px-3 py-3">
            <a href="<?php echo e(route('admin.systemsettings.dashboard', absolute: false)); ?>" class="admin-module-tab is-active bg-[#2f61b3] text-white">
                <i class="fa-solid fa-desktop"></i>
                <span>DASHBOARD</span>
            </a>
            <a href="<?php echo e(route('admin.systemsettings.dashboard', absolute: false)); ?>" class="admin-module-tab bg-white text-neutral-800">
                <i class="fa-solid fa-gears"></i>
                <span>SYSTEM SETTINGS</span>
            </a>
        </div>

        <div class="grid gap-4 p-4 xl:grid-cols-2">
            <?php $__currentLoopData = $settingGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <section class="rounded-xl border border-neutral-300 bg-white shadow-sm">
                    <h2 class="bg-[#2f61b3] px-3 py-2 text-sm font-semibold uppercase tracking-wide text-white"><?php echo e($group); ?></h2>
                    <div class="grid gap-3 p-3 sm:grid-cols-2">
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex min-h-[88px] items-center rounded-xl border border-neutral-300 bg-neutral-50 px-4 py-3 text-sm font-semibold text-neutral-800 shadow-sm">
                                <?php echo e($item); ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\systemsettings\dashboard.blade.php ENDPATH**/ ?>