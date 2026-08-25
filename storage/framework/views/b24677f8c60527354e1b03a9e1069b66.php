<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $__env->yieldContent('title', 'Student Portal'); ?></title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.tsx']); ?>
    </head>
    <body class="bg-neutral-100 text-neutral-900">
        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <?php if(session('status')): ?>
                <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <header class="mb-6 flex flex-col gap-3 border-b border-neutral-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">CodeIgniter-compatible student and parent portal</p>
                    <h1 class="text-2xl font-semibold"><?php echo $__env->yieldContent('title', 'Student Portal'); ?></h1>
                </div>

                <a href="<?php echo e(route('dashboard')); ?>" class="text-sm font-medium text-blue-700">Main dashboard</a>
            </header>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\user\layouts\app.blade.php ENDPATH**/ ?>