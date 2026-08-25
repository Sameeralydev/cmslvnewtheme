<?php $__env->startSection('title', 'Change Password'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('user.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form method="POST" action="<?php echo e(route('user.password.update')); ?>" class="max-w-xl rounded border border-neutral-200 bg-white p-4">
        <?php echo csrf_field(); ?>

        <div class="mb-4">
            <label for="current_password" class="mb-1 block text-sm font-medium">Current password</label>
            <input id="current_password" name="current_password" type="password" class="w-full rounded border border-neutral-300 px-3 py-2" autocomplete="current-password">
            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="password" class="mb-1 block text-sm font-medium">New password</label>
            <input id="password" name="password" type="password" class="w-full rounded border border-neutral-300 px-3 py-2" autocomplete="new-password">
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="mb-1 block text-sm font-medium">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded border border-neutral-300 px-3 py-2" autocomplete="new-password">
        </div>

        <button class="rounded bg-blue-600 px-4 py-2 text-white">Update password</button>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\user\password\edit.blade.php ENDPATH**/ ?>