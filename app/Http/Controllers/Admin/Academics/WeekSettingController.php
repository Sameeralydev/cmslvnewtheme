<?php

namespace App\Http\Controllers\Admin\Academics;

use App\Models\Academics\WeekSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WeekSettingController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        $records = WeekSetting::query()->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search')->toString().'%')->orWhere('note', 'like', '%'.$request->string('search')->toString().'%'))->orderBy('id')->paginate(15)->withQueryString();
        $week = $request->integer('edit') ? WeekSetting::findOrFail($request->integer('edit')) : null;
        return view('admin.academics.curriculum.week-settings', compact('records', 'week'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:week,name'], 'description' => ['nullable', 'string', 'max:1000']]);
        WeekSetting::create(['name' => $data['name'], 'note' => $data['description'] ?? '', 'is_active' => 'yes']);
        return to_route('admin.academics.week-settings.index')->with('success', 'Week added successfully.');
    }

    public function update(Request $request, WeekSetting $weekSetting)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', Rule::unique('week', 'name')->ignore($weekSetting->id)], 'description' => ['nullable', 'string', 'max:1000']]);
        $weekSetting->update(['name' => $data['name'], 'note' => $data['description'] ?? '', 'is_active' => 'yes']);
        return to_route('admin.academics.week-settings.index')->with('success', 'Week updated successfully.');
    }

    public function destroy(WeekSetting $weekSetting)
    {
        $weekSetting->delete();
        return to_route('admin.academics.week-settings.index')->with('success', 'Week deleted successfully.');
    }
}
