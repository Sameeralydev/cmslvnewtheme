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
@endif
