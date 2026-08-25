<?php $__env->startSection('title', 'Front CMS'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-4 rounded border border-neutral-200 bg-white p-4">
        <p class="text-sm text-neutral-500">Media files</p>
        <p class="text-2xl font-semibold"><?php echo e(number_format($mediaCount)); ?></p>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <section class="rounded border border-neutral-200 bg-white">
            <h2 class="border-b border-neutral-200 px-4 py-3 font-semibold">Pages</h2>
            <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="border-b border-neutral-100 px-4 py-3">
                    <h3 class="font-medium"><?php echo e($page->title); ?></h3>
                    <p class="text-sm text-neutral-600"><?php echo e($page->slug); ?></p>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="px-4 py-3 text-sm text-neutral-600">No CMS pages found.</p>
            <?php endif; ?>
        </section>

        <section class="rounded border border-neutral-200 bg-white">
            <h2 class="border-b border-neutral-200 px-4 py-3 font-semibold">Posts</h2>
            <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="border-b border-neutral-100 px-4 py-3">
                    <h3 class="font-medium"><?php echo e($post->title); ?></h3>
                    <p class="text-sm text-neutral-600"><?php echo e($post->slug); ?></p>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="px-4 py-3 text-sm text-neutral-600">No CMS posts found.</p>
            <?php endif; ?>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\frontcms\index.blade.php ENDPATH**/ ?>