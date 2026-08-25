<header>
    <a href="<?php echo e(route('frontend.home')); ?>">
        <?php if(! empty($frontSettings?->logo)): ?>
            <img src="<?php echo e(asset($frontSettings->logo)); ?>" alt="<?php echo e($settings?->name ?? config('app.name', 'Laravel')); ?>" height="48">
        <?php else: ?>
            <?php echo e($settings?->name ?? config('app.name', 'Laravel')); ?>

        <?php endif; ?>
    </a>

    <?php if(! empty($settings?->phone) || ! empty($settings?->email)): ?>
        <p>
            <?php if(! empty($settings?->phone)): ?>
                <span><?php echo e($settings->phone); ?></span>
            <?php endif; ?>

            <?php if(! empty($settings?->email)): ?>
                <span><?php echo e($settings->email); ?></span>
            <?php endif; ?>
        </p>
    <?php endif; ?>
</header>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\frontend\partials\header.blade.php ENDPATH**/ ?>