<?php $__env->startSection('content'); ?>
    <article>
        <h1><?php echo e($page?->title ?? 'Privacy Policy'); ?></h1>

        <?php if(! empty($page?->description)): ?>
            <div><?php echo $page->description; ?></div>
        <?php else: ?>
            <p>Privacy policy content will be published here.</p>
        <?php endif; ?>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\frontend\privacy-policy.blade.php ENDPATH**/ ?>