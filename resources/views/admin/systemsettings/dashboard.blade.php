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

        <div class="grid gap-4 p-4 xl:grid-cols-2">
            @foreach ($settingGroups as $group => $items)
                <section class="rounded-xl border border-neutral-300 bg-white shadow-sm">
                    <h2 class="bg-[#2f61b3] px-3 py-2 text-sm font-semibold uppercase tracking-wide text-white">{{ $group }}</h2>
                    <div class="grid gap-3 p-3 sm:grid-cols-2">
                        @foreach ($items as $item)
                            <div class="flex min-h-[88px] items-center rounded-xl border border-neutral-300 bg-neutral-50 px-4 py-3 text-sm font-semibold text-neutral-800 shadow-sm">{{ $item }}</div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </section>
@endsection
