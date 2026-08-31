@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
    <section class="admin-resource-page general-settings-page">
        <div class="admin-resource-heading"><div><h1>General Settings</h1></div><a class="admin-button" href="{{ route('admin.systemsettings.dashboard.short', absolute: false) }}">Dashboard</a></div>
        <div class="admin-settings-layout">
        @include('admin.systemsettings.partials.nav')
        <form class="admin-settings-form" method="post" action="{{ request()->routeIs('admin.systemsettings.general.legacy') ? route('admin.systemsettings.general.legacy.update', absolute: false) : (request()->routeIs('admin.systemsettings.short') ? route('admin.systemsettings.short.update', absolute: false) : route('admin.systemsettings.general.update', absolute: false)) }}">
            @csrf
            <input type="hidden" name="brc_id" value="{{ $branchId }}">
            <section class="admin-settings-card"><h2>Branch Information</h2><div class="admin-settings-grid">
                <label class="admin-field"><span>Branch</span><select name="brc_id" disabled>@foreach($branches as $item)<option value="{{ $item->id }}" @selected($item->id === $branchId)>{{ $item->name }}</option>@endforeach</select></label>
                @include('admin.systemsettings.partials.field', ['name' => 'branch_name', 'label' => 'Branch Name', 'value' => $branch?->name])
                @include('admin.systemsettings.partials.select', ['name' => 'country_id', 'label' => 'Country', 'items' => $countries, 'value' => $branch?->country_id])
                @include('admin.systemsettings.partials.select', ['name' => 'province_id', 'label' => 'Province', 'items' => $provinces, 'value' => $branch?->province_id])
                @include('admin.systemsettings.partials.select', ['name' => 'division_id', 'label' => 'Division', 'items' => $divisions, 'value' => $branch?->division_id])
                @include('admin.systemsettings.partials.select', ['name' => 'district_id', 'label' => 'District', 'items' => $districts, 'value' => $branch?->district_id])
                @include('admin.systemsettings.partials.select', ['name' => 'tehsils_id', 'label' => 'Block / Town', 'items' => $tehsils, 'value' => $branch?->tehsils_id])
                @include('admin.systemsettings.partials.select', ['name' => 'area_id', 'label' => 'Area', 'items' => $areas, 'value' => $branch?->area_id])
                @include('admin.systemsettings.partials.field', ['name' => 'sch_name', 'label' => 'School Name', 'value' => $setting?->name])
                @include('admin.systemsettings.partials.field', ['name' => 'sch_dise_code', 'label' => 'School Code', 'value' => $setting?->dise_code])
                @include('admin.systemsettings.partials.field', ['name' => 'sch_phone', 'label' => 'Phone', 'value' => $setting?->phone])
                @include('admin.systemsettings.partials.field', ['name' => 'sch_email', 'label' => 'Email', 'value' => $setting?->email, 'type' => 'email'])
                @include('admin.systemsettings.partials.field', ['name' => 'sch_address', 'label' => 'Address', 'value' => $setting?->address, 'textarea' => true])
            </div></section>
            <section class="admin-settings-card"><h2>Academic Session</h2><div class="admin-settings-grid">
                @include('admin.systemsettings.partials.select', ['name' => 'sch_session_id', 'label' => 'Session', 'items' => $sessions, 'value' => $session?->id, 'key' => 'session'])
                @include('admin.systemsettings.partials.field', ['name' => 'start_month', 'label' => 'Session Start Month', 'value' => $session?->start_date, 'type' => 'date'])
                @include('admin.systemsettings.partials.field', ['name' => 'end_month', 'label' => 'Session End Month', 'value' => $session?->end_date, 'type' => 'date'])
            </div></section>
            <section class="admin-settings-card"><h2>Date, Time & Currency</h2><div class="admin-settings-grid">
                @include('admin.systemsettings.partials.field', ['name' => 'sch_date_format', 'label' => 'Date Format', 'value' => $setting?->date_format ?: 'd/m/Y'])
                @include('admin.systemsettings.partials.field', ['name' => 'sch_timezone', 'label' => 'Timezone', 'value' => $setting?->timezone ?: 'Asia/Karachi'])
                @include('admin.systemsettings.partials.select', ['name' => 'currency_id', 'label' => 'Currency', 'items' => $currencies, 'value' => $setting?->currency, 'key' => 'short_name'])
                @include('admin.systemsettings.partials.field', ['name' => 'currency_format', 'label' => 'Currency Format', 'value' => $setting?->currency_format ?: '12345678.00'])
                <label class="admin-field"><span>Currency Symbol Place</span><select name="currency_place"><option value="before_number" @selected(($setting?->currency_place ?: 'after_number') === 'before_number')>Before Number</option><option value="after_number" @selected(($setting?->currency_place ?: 'after_number') === 'after_number')>After Number</option></select></label>
                @include('admin.systemsettings.partials.field', ['name' => 'base_url', 'label' => 'Base URL', 'value' => $setting?->base_url ?: config('app.url')])
                @include('admin.systemsettings.partials.field', ['name' => 'folder_path', 'label' => 'File Upload Path', 'value' => $setting?->folder_path ?: storage_path('app/public')])
            </div></section>
            <button class="admin-button admin-button-primary" type="submit">Save Changes</button>
        </form>
        </div>
    </section>
@endsection
