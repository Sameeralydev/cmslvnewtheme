@extends('admin.layouts.app')

@section('title', $config['label'])

@section('content')
    <section class="admin-resource-page system-settings-resource">
        <div class="admin-resource-heading">
            <div><h1>{{ $config['label'] }}</h1></div>
            <a class="admin-button admin-button-primary" href="{{ route('admin.systemsettings.dashboard.short', absolute: false) }}">Dashboard</a>
        </div>
        <div class="admin-settings-layout">
            @include('admin.systemsettings.partials.nav')
            <div>

        @if (count($fields))
            <form class="admin-resource-form" method="post" action="{{ $editRecord ? route('admin.systemsettings.update', ['slug' => $slug, 'id' => $editRecord->id], false) : route('admin.systemsettings.store', ['slug' => $slug], false) }}">
                @csrf
                <div class="admin-resource-fields">
                    @foreach ($fields as $field)
                        <label class="admin-field"><span>{{ str($field)->replace('_', ' ')->title() }}</span>
                            @if ($field === 'is_active')
                                <select name="{{ $field }}"><option value="1" @selected(($editRecord->{$field} ?? 1) == 1)>Active</option><option value="0" @selected(($editRecord->{$field} ?? 1) == 0)>Inactive</option></select>
                            @elseif (str_contains($field, 'address') || $field === 'description')
                                <textarea name="{{ $field }}">{{ $editRecord->{$field} ?? '' }}</textarea>
                            @else
                                <input name="{{ $field }}" value="{{ $editRecord->{$field} ?? '' }}" type="{{ str_contains($field, 'email') ? 'email' : (str_contains($field, 'date') ? 'date' : 'text') }}">
                            @endif
                        </label>
                    @endforeach
                </div>
                <button class="admin-button admin-button-primary" type="submit">{{ $editRecord ? 'Update' : 'Save' }}</button>
                @if ($editRecord)<a class="admin-button" href="{{ route('admin.systemsettings.resource', ['slug' => $slug], false) }}">Cancel</a>@endif
            </form>
        @endif

        <div class="admin-resource-table-wrap">
            <table class="admin-resource-table"><thead><tr>
                @foreach (array_values(array_filter($columns, fn ($column) => $column !== 'updated_at')) as $column)<th>{{ str($column)->replace('_', ' ')->title() }}</th>@endforeach
                <th>Actions</th>
            </tr></thead><tbody>
                @forelse ($records as $record)
                    <tr>@foreach (array_values(array_filter($columns, fn ($column) => $column !== 'updated_at')) as $column)<td>{{ is_scalar($record->{$column} ?? null) ? $record->{$column} : '' }}</td>@endforeach
                        <td class="admin-resource-actions">
                            <a class="admin-icon-button" href="{{ route('admin.systemsettings.resource', ['slug' => $slug, 'edit' => $record->id], false) }}" aria-label="Edit"><i class="fa-solid fa-pen"></i></a>
                            <form method="post" action="{{ route('admin.systemsettings.destroy', ['slug' => $slug, 'id' => $record->id], false) }}">@csrf<button class="admin-icon-button is-danger" type="submit" aria-label="Delete"><i class="fa-solid fa-trash"></i></button></form>
                        </td>
                    </tr>
                @empty <tr><td colspan="99">No records found.</td></tr>@endforelse
            </tbody></table>
        </div>
        @if (method_exists($records, 'links'))<div class="admin-pagination">{{ $records->links() }}</div>@endif
            </div>
        </div>
    </section>
@endsection
