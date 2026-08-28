<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/themes/default/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-toast.css') }}">
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
    <link rel="stylesheet" href="{{ asset('assets/css/account-modules.css') }}">
    @stack('styles')
</head>

<body class="admin-body min-h-full">
    <div class="admin-shell">
        @include('admin.partials.sidebar')

        <div class="admin-content-wrap">
            @include('admin.partials.header')
            @include('admin.partials.navbar')

            <main class="admin-main">
                @include('admin.partials.alerts')
                @yield('content')
            </main>

            @include('admin.partials.footer')
        </div>
    </div>

    <script>
        window.adminRoutes = {
            dashboard: @json(route('admin.dashboard')),
            staff: @json(route('admin.staff.index')),
            report: @json(route('admin.report.index')),
            frontcms: @json(route('admin.frontcms.index')),
            membership: @json(route('admin.membership.index')),
            qms: @json(route('admin.qms.index')),
            systemNotification: @json(route('admin.system-notification.index')),
        };
    </script>
    @stack('scripts')
</body>

</html>
