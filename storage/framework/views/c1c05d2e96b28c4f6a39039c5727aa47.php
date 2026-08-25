<footer>
    <?php if(! empty($settings?->address)): ?>
        <address><?php echo e($settings->address); ?></address>
    <?php endif; ?>

    <?php if(! empty($frontSettings?->footer_text)): ?>
        <p><?php echo e($frontSettings->footer_text); ?></p>
    <?php endif; ?>

    <p>&copy; <?php echo e(now()->year); ?> <?php echo e($settings?->name ?? config('app.name', 'Laravel')); ?></p>
</footer>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\frontend\partials\footer.blade.php ENDPATH**/ ?>