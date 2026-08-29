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
            $toastType = 'warning';
        } elseif (session('info')) {
            $toastMessage = session('info');
            $toastType = 'info';
        }
    }
    $toastType = $toastType ?: 'success';
    $toastClass = match ($toastType) {
        'error' => 'admin-toast-error',
        'warning' => 'admin-toast-warning',
        default => 'admin-toast-success',
    };
    $toastIcon = match ($toastType) {
        'error' => '&#10005;',
        'warning' => '!',
        'info' => 'i',
        default => '&#10003;',
    };
@endphp

@if ($toastMessage)
<<<<<<< HEAD
    <div id="appToast" data-toast class="admin-toast {{ $toastClass }} toast-slide-in" role="status">
        <span class="admin-toast-icon">{!! $toastIcon !!}</span>
        <span class="admin-toast-content"><strong>{{ ucfirst($toastType) }}</strong><small>{{ $toastMessage }}</small></span>
        <button type="button" class="admin-toast-close" data-toast-close aria-label="Close">&times;</button>
        <span class="admin-toast-progress"></span>
    </div>
@endif

@if ($errors->any() && !$toastMessage)
    <div data-toast class="admin-toast admin-toast-error toast-slide-in" role="alert">
        <span class="admin-toast-icon">&#10005;</span><span class="admin-toast-content"><strong>Error</strong><small>Please correct the highlighted fields.</small></span><button type="button" class="admin-toast-close" data-toast-close aria-label="Close">&times;</button><span class="admin-toast-progress"></span>
=======
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
>>>>>>> d2db107 (feat: Payment Voucher & Expense Bill 2-Up PDF print download, table layout and account modules updates)
    </div>
@endif
