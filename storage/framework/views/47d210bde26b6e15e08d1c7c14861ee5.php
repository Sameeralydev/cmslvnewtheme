<?php $__env->startSection('content'); ?>
    <section>
        <h1><?php echo e($homePage?->title ?? ($settings?->name ?? config('app.name', 'Laravel'))); ?></h1>

        <?php if(! empty($homePage?->description)): ?>
            <div><?php echo $homePage->description; ?></div>
        <?php else: ?>
            <p>Welcome to <?php echo e($settings?->name ?? config('app.name', 'Laravel')); ?>.</p>
        <?php endif; ?>
    </section>

    <section>
        <h2>Branches</h2>

        <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article>
                <h3><a href="<?php echo e(route('frontend.branch', $branch)); ?>"><?php echo e($branch->name); ?></a></h3>
                <?php if(! empty($branch->websiteurl)): ?>
                    <p><?php echo e($branch->websiteurl); ?></p>
                <?php endif; ?>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No active branches are available.</p>
        <?php endif; ?>
    </section>

    <section>
        <h2>Latest Updates</h2>

        <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article>
                <h3><?php echo e($post->title); ?></h3>
                <?php if(! empty($post->publish_date)): ?>
                    <time datetime="<?php echo e($post->publish_date->toDateString()); ?>"><?php echo e($post->publish_date->toFormattedDateString()); ?></time>
                <?php endif; ?>
                <p><?php echo e(\Illuminate\Support\Str::limit(strip_tags((string) $post->description), 160)); ?></p>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No published updates are available.</p>
        <?php endif; ?>
    </section>

    <section>
        <h2>Gallery</h2>

        <?php $__empty_1 = true; $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php if(! empty($item->thumb_path) || ! empty($item->image)): ?>
                <img src="<?php echo e(asset($item->thumb_path ?: $item->image)); ?>" alt="<?php echo e($item->img_name ?: 'Gallery image'); ?>" height="120">
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No gallery items are available.</p>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\frontend\index.blade.php ENDPATH**/ ?>