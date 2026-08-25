<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo e($seo['title'] ?? config('app.name', 'Laravel')); ?></title>
        <meta name="description" content="<?php echo e($seo['description'] ?? ''); ?>">
        <meta name="keywords" content="<?php echo e($seo['keywords'] ?? ''); ?>">
        <link rel="canonical" href="<?php echo e($seo['canonical_url'] ?? url()->current()); ?>">
        <meta property="og:title" content="<?php echo e($seo['og_title'] ?? ($seo['title'] ?? config('app.name', 'Laravel'))); ?>">
        <meta property="og:description" content="<?php echo e($seo['og_description'] ?? ($seo['description'] ?? '')); ?>">
        <?php if(! empty($seo['og_image'])): ?>
            <meta property="og:image" content="<?php echo e(asset($seo['og_image'])); ?>">
        <?php endif; ?>
    </head>
    <body>
        <?php echo $__env->make('frontend.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('frontend.partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main>
            <?php echo $__env->make('frontend.partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <?php echo $__env->make('frontend.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\frontend\layouts\app.blade.php ENDPATH**/ ?>