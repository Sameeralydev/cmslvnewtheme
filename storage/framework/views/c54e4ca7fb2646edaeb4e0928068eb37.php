<?php $__env->startSection('title', 'Membership'); ?>

<?php $__env->startSection('content'); ?>
    <form method="GET" action="<?php echo e(route('admin.membership.index')); ?>" class="mb-4 flex gap-2">
        <input name="search" value="<?php echo e(request('search')); ?>" class="w-full rounded border border-neutral-300 px-3 py-2" placeholder="Search members">
        <button class="rounded bg-blue-600 px-4 py-2 text-white">Search</button>
    </form>

    <div class="overflow-x-auto rounded border border-neutral-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-4 py-3">Card No</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Member ID</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 py-3"><?php echo e($member->library_card_no); ?></td>
                        <td class="px-4 py-3"><?php echo e($member->member_type); ?></td>
                        <td class="px-4 py-3"><?php echo e($member->member_id); ?></td>
                        <td class="px-4 py-3"><?php echo e($member->is_active); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="px-4 py-3 text-neutral-600">No membership records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($members->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\membership\index.blade.php ENDPATH**/ ?>