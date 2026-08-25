<?php if(session('status')): ?>
    <p role="status"><?php echo e(session('status')); ?></p>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div role="alert">
        <p>Please correct the highlighted fields.</p>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\frontend\partials\alerts.blade.php ENDPATH**/ ?>