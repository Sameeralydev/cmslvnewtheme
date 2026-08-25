<?php $__env->startSection('content'); ?>
    <section>
        <h1><?php echo e($branch->name); ?></h1>

        <?php if(! empty($branch->websiteurl)): ?>
            <p><?php echo e($branch->websiteurl); ?></p>
        <?php endif; ?>

        <?php if(! empty($settings?->address)): ?>
            <p><?php echo e($settings->address); ?></p>
        <?php endif; ?>
    </section>

    <section>
        <h2>Pages</h2>

        <?php $__empty_1 = true; $__currentLoopData = $branch->pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article>
                <h3><?php echo e($page->title); ?></h3>
                <p><?php echo e(\Illuminate\Support\Str::limit(strip_tags((string) $page->description), 120)); ?></p>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No branch pages are available.</p>
        <?php endif; ?>
    </section>

    <section>
        <h2>Updates</h2>

        <?php $__empty_1 = true; $__currentLoopData = $branch->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article>
                <h3><?php echo e($post->title); ?></h3>
                <p><?php echo e(\Illuminate\Support\Str::limit(strip_tags((string) $post->description), 120)); ?></p>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No branch updates are available.</p>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\frontend\branch.blade.php ENDPATH**/ ?>