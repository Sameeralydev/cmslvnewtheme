<?php ($isHrmsRoute = request()->routeIs('admin.hrms.*')); ?>

<footer class="main-footer <?php echo e($isHrmsRoute ? 'bg-transparent px-0 py-0 text-[11px] text-[#666]' : 'border-t border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-500'); ?> text-center">
    <?php if($isHrmsRoute): ?>
        <div class="mx-auto mt-2 w-[62%] bg-white py-2.5 shadow-[0_1px_2px_rgba(15,23,42,0.06)]">
            &copy; <?php echo e(now()->year); ?> <?php echo e(config('app.name', 'Laravel')); ?>

        </div>
    <?php else: ?>
        &copy; <?php echo e(now()->year); ?> <?php echo e(config('app.name', 'Laravel')); ?>

    <?php endif; ?>
</footer>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views/admin/partials/footer.blade.php ENDPATH**/ ?>