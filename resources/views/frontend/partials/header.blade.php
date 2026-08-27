<header class="frontend-header">
    <div class="frontend-container frontend-header-inner">
    <a class="frontend-brand" href="{{ route('frontend.home') }}">
        @if (! empty($frontSettings?->logo))
            <img src="{{ asset($frontSettings->logo) }}" alt="{{ $settings?->name ?? config('app.name', 'Laravel') }}" height="48">
        @else
            <span class="frontend-brand-mark">{{ strtoupper(substr((string) ($settings?->name ?? config('app.name', 'Laravel')), 0, 1)) }}</span>
            <span>{{ $settings?->name ?? config('app.name', 'Laravel') }}</span>
        @endif
    </a>

    @if (! empty($settings?->phone) || ! empty($settings?->email))
        <p class="frontend-contact">
            @if (! empty($settings?->phone))
                <span>{{ $settings->phone }}</span>
            @endif

            @if (! empty($settings?->email))
                <span>{{ $settings->email }}</span>
            @endif
        </p>
    @endif
    </div>
</header>
