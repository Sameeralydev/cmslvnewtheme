@php
    $toastType = session('toast_type');
    $toastMessage = session('toast_message');

    if (! $toastMessage) {
        if ($errors->any()) {
            $toastMessage = $errors->first();
            $toastType = 'error';
        } elseif (session('success') || session('status') || !empty($success_msg)) {
            $toastMessage = session('success') ?? session('status') ?? ($success_msg ?? null);
            $toastType = $toastType ?: 'success';
        } elseif (session('error') || !empty($error_msg)) {
            $toastMessage = session('error') ?? ($error_msg ?? null);
            $toastType = $toastType ?: 'error';
        } elseif (session('warning')) {
            $toastMessage = session('warning');
            $toastType = $toastType ?: 'warning';
        } elseif (session('info')) {
            $toastMessage = session('info');
            $toastType = $toastType ?: 'info';
        }
    }

    $toastType = $toastType ?: 'success';

    $toastConfig = [
        'success' => [
            'bg' => 'bg-green-100',
            'border' => 'border-green-500',
            'text' => 'text-green-700',
            'progress' => 'bg-green-500',
            'title' => 'Success',
            'icon' => '✓',
        ],

        'error' => [
            'bg' => 'bg-red-100',
            'border' => 'border-red-500',
            'text' => 'text-red-700',
            'progress' => 'bg-red-500',
            'title' => 'Error',
            'icon' => '×',
        ],

        'warning' => [
            'bg' => 'bg-yellow-100',
            'border' => 'border-yellow-500',
            'text' => 'text-yellow-700',
            'progress' => 'bg-yellow-500',
            'title' => 'Warning',
            'icon' => '!',
        ],

        'info' => [
            'bg' => 'bg-blue-100',
            'border' => 'border-blue-500',
            'text' => 'text-blue-700',
            'progress' => 'bg-blue-500',
            'title' => 'Information',
            'icon' => 'i',
        ],
    ];

    $config = $toastConfig[$toastType] ?? $toastConfig['success'];
@endphp

@if ($toastMessage)
<div
    id="appToast"
    class="fixed top-3.5 right-3.5 z-[9999] w-[230px] max-w-[calc(100vw-2rem)]
           overflow-hidden rounded-md
           {{ $config['bg'] }}
           border-l-[3px] {{ $config['border'] }}
           {{ $config['text'] }}
           shadow-md
           toast-slide-in"
>
    <div class="flex items-start gap-2 px-2.5 py-1.5">
        <!-- Icon -->
        <div class="flex h-4 w-4 mt-0.5 shrink-0 items-center justify-center rounded-full
                    {{ $config['progress'] }} text-white text-[9px] font-bold leading-none">
            {{ $config['icon'] }}
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <p class="text-[11px] font-bold leading-tight">
                {{ $config['title'] }}
            </p>

            <p class="text-[10px] leading-tight mt-0.5 opacity-90 truncate">
                {{ $toastMessage }}
            </p>
        </div>

        <!-- Close Button -->
        <button
            type="button"
            onclick="hideToast()"
            class="text-xs font-bold opacity-60 hover:opacity-100 transition leading-none px-0.5"
            aria-label="Close"
        >
            ×
        </button>
    </div>

    <!-- 3 Second Progress Bar Track & Loading Line -->
    <div class="toast-progress-track">
        <div
            id="toastProgress"
            class="toast-progress {{ $config['progress'] }}"
        ></div>
    </div>
</div>
@endif

<script>
/* =========================================
   Laravel Toast
   ========================================= */

function hideToast() {
    const toast = document.getElementById('appToast');
    if (!toast) return;

    toast.classList.remove('toast-slide-in');
    toast.classList.add('toast-slide-out');

    setTimeout(() => {
        toast.remove();
    }, 350);
}

/* Automatically hide after 3 seconds */
document.addEventListener('DOMContentLoaded', function () {
    const toast = document.getElementById('appToast');
    if (!toast) return;

    setTimeout(() => {
        hideToast();
    }, 3000);
});
</script>
