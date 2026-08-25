<?php $__env->startSection('content'); ?>
    <section>
        <h1>Franchises</h1>

        <?php $__empty_1 = true; $__currentLoopData = $franchises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $franchise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article>
                <h2><a href="<?php echo e(route('frontend.branch', $franchise->id)); ?>"><?php echo e($franchise->name); ?></a></h2>
                <?php if(! empty($franchise->websiteurl)): ?>
                    <p><?php echo e($franchise->websiteurl); ?></p>
                <?php endif; ?>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No franchise locations are available.</p>
        <?php endif; ?>

        <?php echo e($franchises->links()); ?>

    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\frontend\franchises.blade.php ENDPATH**/ ?>