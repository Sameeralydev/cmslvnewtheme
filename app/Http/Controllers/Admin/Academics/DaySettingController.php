<?php

namespace App\Http\Controllers\Admin\Academics;

use App\Models\Academics\DaySetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\Academics\TermSetting;
use App\Models\Academics\WeekSetting;

class DaySettingController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        $records = DaySetting::query()->with(['term', 'week'])->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search')->toString().'%')->orWhere('note', 'like', '%'.$request->string('search')->toString().'%'))->orderBy('id')->paginate(15)->withQueryString();
        $day = $request->integer('edit') ? DaySetting::findOrFail($request->integer('edit')) : null;
        $terms = TermSetting::query()->orderBy('id')->get(); $weeks = WeekSetting::query()->orderBy('id')->get();
        return view('admin.academics.curriculum.day-settings', compact('records', 'day', 'terms', 'weeks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:day,name'], 'description' => ['nullable', 'string', 'max:1000'], 'term_id' => ['required', 'exists:term,id'], 'week_id' => ['required', 'exists:week,id'], 'date' => ['nullable', 'date'], 'period' => ['nullable', 'string', 'max:50']]);
        DaySetting::create(['name' => $data['name'], 'note' => $data['description'] ?? '', 'term_id' => $data['term_id'], 'week_id' => $data['week_id'], 'date' => $data['date'] ?? null, 'period' => $data['period'] ?? null, 'is_working_day' => $request->boolean('is_working_day', true), 'is_active' => 'yes']);
        return to_route('admin.academics.day-settings.index')->with('success', 'Day added successfully.');
    }

    public function update(Request $request, DaySetting $daySetting)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', Rule::unique('day', 'name')->ignore($daySetting->id)], 'description' => ['nullable', 'string', 'max:1000'], 'term_id' => ['required', 'exists:term,id'], 'week_id' => ['required', 'exists:week,id'], 'date' => ['nullable', 'date'], 'period' => ['nullable', 'string', 'max:50']]);
        $daySetting->update(['name' => $data['name'], 'note' => $data['description'] ?? '', 'term_id' => $data['term_id'], 'week_id' => $data['week_id'], 'date' => $data['date'] ?? null, 'period' => $data['period'] ?? null, 'is_working_day' => $request->boolean('is_working_day', true), 'is_active' => 'yes']);
        return to_route('admin.academics.day-settings.index')->with('success', 'Day updated successfully.');
    }

    public function destroy(DaySetting $daySetting)
    {
        $daySetting->delete();
        return to_route('admin.academics.day-settings.index')->with('success', 'Day deleted successfully.');
    }
}
