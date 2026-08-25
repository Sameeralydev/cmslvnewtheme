<?php $__env->startSection('title', 'Fees'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('user.partials.nav', ['moduleKey' => 'fees'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="mb-4 rounded border border-neutral-200 bg-white p-4">
        <h2 class="mb-3 text-lg font-semibold">Fee Totals</h2>
        <div class="grid gap-3 sm:grid-cols-3">
            <p>Assigned: <?php echo e(number_format($feeSummary['totals']['assigned_amount'], 2)); ?></p>
            <p>Paid: <?php echo e(number_format($feeSummary['totals']['paid_amount'], 2)); ?></p>
            <p>Balance: <?php echo e(number_format($feeSummary['totals']['balance'], 2)); ?></p>
        </div>
    </section>

    <section class="overflow-x-auto rounded border border-neutral-200 bg-white">
        <div class="border-b border-neutral-200 px-4 py-3">
            <p class="text-sm text-neutral-500">Legacy tables: student_fees_assign, student_fees_deposite_details</p>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-4 py-3">Bill No</th>
                    <th class="px-4 py-3">Fee Month</th>
                    <th class="px-4 py-3">Amount</th>
                    <th class="px-4 py-3">Paid</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                <?php $__empty_1 = true; $__currentLoopData = $feeSummary['deposits']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 py-3"><?php echo e($deposit->bill_no); ?></td>
                        <td class="px-4 py-3"><?php echo e($deposit->fee_month); ?></td>
                        <td class="px-4 py-3"><?php echo e(number_format((float) $deposit->amount, 2)); ?></td>
                        <td class="px-4 py-3"><?php echo e(number_format((float) $deposit->paid_amount, 2)); ?></td>
                        <td class="px-4 py-3"><?php echo e($deposit->status); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-neutral-600">No fee deposit records found for the selected student.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\user\fees\index.blade.php ENDPATH**/ ?>