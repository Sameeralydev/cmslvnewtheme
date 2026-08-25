<?php $__env->startSection('content'); ?>
    <article>
        <p><a href="<?php echo e(route('frontend.branch', $branchRecord)); ?>"><?php echo e($branchRecord->name); ?></a></p>
        <h1><?php echo e($page->title); ?></h1>

        <?php if(! empty($page->feature_image)): ?>
            <img src="<?php echo e(asset($page->feature_image)); ?>" alt="<?php echo e($page->title); ?>" height="240">
        <?php endif; ?>

        <div><?php echo $page->description; ?></div>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\frontend\page.blade.php ENDPATH**/ ?>