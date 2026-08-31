@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
    <section class="admin-dashboard-section system-settings-dashboard">
        <nav class="admin-module-tabs system-settings-tabs" aria-label="System settings navigation">
            <a href="{{ route('admin.systemsettings.dashboard', absolute: false) }}" class="admin-module-tab is-active">
                <i class="fa-solid fa-desktop"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.systemsettings.general', absolute: false) }}" class="admin-module-tab">
                <i class="fa-solid fa-gears"></i>
                <span>System Settings</span>
            </a>
        </nav>

        <div class="system-settings-dashboard-grid">
            @foreach ($settingGroups as $group => $items)
                <section class="system-settings-group">
                    <h2>{{ $group }}</h2>
                    <div class="system-settings-group-grid">
                        @foreach ($items as $item)
                            <div class="system-settings-card">{{ $item }}</div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </section>
@endsection
