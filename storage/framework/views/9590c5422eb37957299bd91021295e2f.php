<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Staff Forgot Password - <?php echo e(config('app.name', 'Laravel')); ?></title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.tsx']); ?>
    </head>
    <body class="min-h-screen bg-neutral-100 text-neutral-900">
        <main class="flex min-h-screen items-center justify-center px-4 py-10">
            <section class="w-full max-w-md rounded-lg border border-neutral-200 bg-white p-6 shadow-sm">
                <h1 class="text-2xl font-semibold">Staff Forgot Password</h1>

                <?php if(session('status')): ?>
                    <p class="mt-4 rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-800"><?php echo e(session('status')); ?></p>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('staff.forgot_password', absolute: false)); ?>" class="mt-6 space-y-4">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label for="staff-forgot-email" class="mb-1 block text-sm font-medium text-neutral-700">Email</label>
                        <input id="staff-forgot-email" name="email" type="email" value="<?php echo e(old('email')); ?>" autocomplete="email" required autofocus class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm outline-none focus:border-neutral-900 focus:ring-2 focus:ring-neutral-200">
                        <?php $__errorArgs = ['email'];
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

                    <button type="submit" class="w-full rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800">Send reset link</button>
                </form>

                <p class="mt-4 text-center text-sm">
                    <a href="<?php echo e(route('staff.login', absolute: false)); ?>" class="font-medium text-blue-700">Back to staff login</a>
                </p>
            </section>
        </main>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\staff\auth\forgot-password.blade.php ENDPATH**/ ?>