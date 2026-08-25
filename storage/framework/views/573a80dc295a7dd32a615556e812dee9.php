<?php $__env->startSection('content'); ?>
    <article>
        <p><a href="<?php echo e(route('frontend.branch', $branchRecord)); ?>"><?php echo e($branchRecord->name); ?></a></p>
        <h1><?php echo e($post->title); ?></h1>

        <?php if(! empty($post->publish_date)): ?>
            <time datetime="<?php echo e($post->publish_date->toDateString()); ?>"><?php echo e($post->publish_date->toFormattedDateString()); ?></time>
        <?php endif; ?>

        <?php if(! empty($post->feature_image)): ?>
            <img src="<?php echo e(asset($post->feature_image)); ?>" alt="<?php echo e($post->title); ?>" height="240">
        <?php endif; ?>

        <div><?php echo $post->description; ?></div>
    </article>

    <section>
        <h2>Related Updates</h2>

        <?php $__empty_1 = true; $__currentLoopData = $relatedPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedPost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article>
                <h3><?php echo e($relatedPost->title); ?></h3>
                <p><?php echo e(\Illuminate\Support\Str::limit(strip_tags((string) $relatedPost->description), 120)); ?></p>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No related updates are available.</p>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\frontend\read.blade.php ENDPATH**/ ?>