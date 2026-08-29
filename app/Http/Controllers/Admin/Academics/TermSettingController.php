<?php

namespace App\Http\Controllers\Admin\Academics;

use App\Models\Academics\TermSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class TermSettingController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        $records = TermSetting::query()
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%"));
            })
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $term = $request->integer('edit') ? TermSetting::findOrFail($request->integer('edit')) : null;

        return view('admin.academics.curriculum.term-settings', compact('records', 'term'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:term,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'start_date' => ['nullable', 'date'], 'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        DB::transaction(function () use ($data, $request): void {
            if ($request->boolean('is_active')) TermSetting::query()->update(['is_active' => 'no']);
            TermSetting::create([
            'name' => $data['name'],
            'note' => $data['description'] ?? '',
            'start_date' => $data['start_date'] ?? null, 'end_date' => $data['end_date'] ?? null,
            'is_active' => $request->boolean('is_active') ? 'yes' : 'no',
            ]);
        });

        return to_route('admin.academics.term-settings.index')->with('success', 'Term added successfully.');
    }

    public function update(Request $request, TermSetting $termSetting)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('term', 'name')->ignore($termSetting->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'start_date' => ['nullable', 'date'], 'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        DB::transaction(function () use ($data, $request, $termSetting): void {
        if ($request->boolean('is_active')) TermSetting::query()->where('id', '!=', $termSetting->id)->update(['is_active' => 'no']);
        $termSetting->update([
            'name' => $data['name'],
            'note' => $data['description'] ?? '',
            'start_date' => $data['start_date'] ?? null, 'end_date' => $data['end_date'] ?? null,
            'is_active' => $request->boolean('is_active') ? 'yes' : 'no',
        ]);
        });

        return to_route('admin.academics.term-settings.index')->with('success', 'Term updated successfully.');
    }

    public function destroy(TermSetting $termSetting)
    {
        $termSetting->delete();

        return to_route('admin.academics.term-settings.index')->with('success', 'Term deleted successfully.');
    }
}
