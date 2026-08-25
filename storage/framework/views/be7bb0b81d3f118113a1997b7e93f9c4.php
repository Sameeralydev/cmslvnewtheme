<?php $__env->startSection('title', 'System Notifications'); ?>

<?php $__env->startSection('content'); ?>
    <form method="GET" action="<?php echo e(route('admin.system-notification.index')); ?>" class="mb-4 grid gap-3 rounded border border-neutral-200 bg-white p-4 md:grid-cols-5">
        <input name="search" value="<?php echo e(request('search')); ?>" class="rounded border border-neutral-300 px-3 py-2 md:col-span-2" placeholder="Search title, description, type">
        <select name="brc_id" class="rounded border border-neutral-300 px-3 py-2">
            <option value="">All branches</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($branch->id); ?>" <?php if((string) request('brc_id') === (string) $branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <input name="type" value="<?php echo e(request('type')); ?>" class="rounded border border-neutral-300 px-3 py-2" placeholder="Type">
        <button class="rounded bg-blue-600 px-4 py-2 text-white">Filter</button>
    </form>

    <div class="overflow-x-auto rounded border border-neutral-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">For</th>
                    <th class="px-4 py-3">Branch</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium"><?php echo e($notification->notification_title); ?></p>
                            <p class="text-neutral-600"><?php echo e(\Illuminate\Support\Str::limit($notification->notification_desc, 100)); ?></p>
                        </td>
                        <td class="px-4 py-3"><?php echo e($notification->notification_type); ?></td>
                        <td class="px-4 py-3"><?php echo e($notification->notification_for); ?></td>
                        <td class="px-4 py-3"><?php echo e($notification->branch?->name ?? '-'); ?></td>
                        <td class="px-4 py-3"><?php echo e($notification->date?->format('Y-m-d H:i')); ?></td>
                        <td class="px-4 py-3"><?php echo e($notification->is_active); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="px-4 py-3 text-neutral-600">No system notifications found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($notifications->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\system-notifications\index.blade.php ENDPATH**/ ?>