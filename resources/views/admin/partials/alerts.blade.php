@php
    $toastMessage = session('toast_message') ?? session('success') ?? session('status') ?? session('error');
    $toastType = session('toast_type', session('error') ? 'error' : 'success');
@endphp

@if ($toastMessage)
    <div id="appToast" data-toast class="admin-toast {{ $toastType === 'error' ? 'admin-toast-error' : ($toastType === 'warning' ? 'admin-toast-warning' : 'admin-toast-success') }} toast-slide-in" role="status">
        <span class="admin-toast-icon">@if($toastType === 'error')&#10005;@elseif($toastType === 'warning')!@else&#10003;@endif</span>
        <span class="admin-toast-content"><strong>{{ ucfirst($toastType) }}</strong><small>{{ $toastMessage }}</small></span>
        <button type="button" class="admin-toast-close" data-toast-close aria-label="Close">&times;</button>
        <span class="admin-toast-progress"></span>
    </div>
@endif

@if ($errors->any())
    <div data-toast class="admin-toast admin-toast-error toast-slide-in" role="alert">
        <span class="admin-toast-icon">&#10005;</span>
        <span class="admin-toast-content"><strong>Error</strong><small>Please correct the highlighted fields.</small></span>
        <button type="button" class="admin-toast-close" data-toast-close aria-label="Close">&times;</button>
        <span class="admin-toast-progress"></span>
    </div>
@endif
