<?php

namespace App\Http\Controllers\Admin\Academics;

use App\Models\Academics\DaySetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DaySettingController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        $records = DaySetting::query()->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search')->toString().'%')->orWhere('note', 'like', '%'.$request->string('search')->toString().'%'))->orderBy('id')->paginate(15)->withQueryString();
        $day = $request->integer('edit') ? DaySetting::findOrFail($request->integer('edit')) : null;
        return view('admin.academics.curriculum.day-settings', compact('records', 'day'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:day,name'], 'description' => ['nullable', 'string', 'max:1000']]);
        DaySetting::create(['name' => $data['name'], 'note' => $data['description'] ?? '', 'is_active' => 'yes']);
        return to_route('admin.academics.day-settings.index')->with('success', 'Day added successfully.');
    }

    public function update(Request $request, DaySetting $daySetting)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', Rule::unique('day', 'name')->ignore($daySetting->id)], 'description' => ['nullable', 'string', 'max:1000']]);
        $daySetting->update(['name' => $data['name'], 'note' => $data['description'] ?? '', 'is_active' => 'yes']);
        return to_route('admin.academics.day-settings.index')->with('success', 'Day updated successfully.');
    }

    public function destroy(DaySetting $daySetting)
    {
        $daySetting->delete();
        return to_route('admin.academics.day-settings.index')->with('success', 'Day deleted successfully.');
    }
}
