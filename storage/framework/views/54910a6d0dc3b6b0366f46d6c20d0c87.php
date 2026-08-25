<?php $__env->startSection('title', 'Change Username'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('user.partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form method="POST" action="<?php echo e(route('user.username.update')); ?>" class="max-w-xl rounded border border-neutral-200 bg-white p-4">
        <?php echo csrf_field(); ?>

        <div class="mb-4">
            <label for="username" class="mb-1 block text-sm font-medium">Username</label>
            <input id="username" name="username" value="<?php echo e(old('username', auth()->user()->username ?? '')); ?>" class="w-full rounded border border-neutral-300 px-3 py-2" autocomplete="username">
            <?php $__errorArgs = ['username'];
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

        <button class="rounded bg-blue-600 px-4 py-2 text-white">Update username</button>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\user\username\edit.blade.php ENDPATH**/ ?>