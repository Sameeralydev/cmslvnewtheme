<footer class="frontend-footer">
    <div class="frontend-container">
    @if (! empty($settings?->address))
        <address>{{ $settings->address }}</address>
    @endif

    @if (! empty($frontSettings?->footer_text))
        <p>{{ $frontSettings->footer_text }}</p>
    @endif

    <p>&copy; {{ now()->year }} {{ $settings?->name ?? config('app.name', 'Laravel') }}</p>
    </div>
</footer>
