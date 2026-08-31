@php
    $settingsTabs = [
        ['General Setting', 'admin.systemsettings.short'], ['Logo', 'admin.systemsettings.resource', 'logo'],
        ['Login Page Background', 'admin.systemsettings.resource', 'loginpagebackground'], ['Backend Theme', 'admin.systemsettings.resource', 'backendtheme'],
        ['Mobile App', 'admin.systemsettings.resource', 'mobileapp'], ['Student / Guardian Panel', 'admin.systemsettings.resource', 'studentguardianpanel'],
        ['Royalty', 'admin.systemsettings.resource', 'royalty'], ['Fees', 'admin.systemsettings.resource', 'fees'],
        ['ID Auto Generation', 'admin.systemsettings.resource', 'idautogeneration'], ['Attendance Type', 'admin.systemsettings.resource', 'attendancetype'],
        ['Maintenance', 'admin.systemsettings.resource', 'maintenance'], ['Miscellaneous', 'admin.systemsettings.resource', 'miscellaneous'],
    ];
@endphp
<nav class="admin-settings-subnav" aria-label="System settings pages">
    @foreach ($settingsTabs as $tab)
        @php $tabSlug = $tab[2] ?? null; $isActive = $tabSlug ? request()->route('slug') === $tabSlug : request()->routeIs($tab[1]); @endphp
        <a class="admin-settings-subnav-link {{ $isActive ? 'is-active' : '' }}" href="{{ $tabSlug ? route($tab[1], ['slug' => $tabSlug], false) : route($tab[1], absolute: false) }}">{{ $tab[0] }}</a>
    @endforeach
</nav>
