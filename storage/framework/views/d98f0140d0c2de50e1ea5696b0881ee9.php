<?php $__currentLoopData = ['success', 'status', 'error']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flashType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(session($flashType)): ?>
        <div class="mb-4 rounded border px-4 py-3 text-sm <?php echo e($flashType === 'error' ? 'border-red-200 bg-red-50 text-red-800' : 'border-green-200 bg-green-50 text-green-800'); ?>">
            <?php echo e(session($flashType)); ?>

        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($errors->any()): ?>
    <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-medium">Please correct the highlighted fields.</p>
        <ul class="mt-2 list-disc pl-5">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\partials\alerts.blade.php ENDPATH**/ ?>