<?php

namespace App\Http\Controllers\Admin\Academics;

use App\Models\Academics\WeekSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\Academics\TermSetting;

class WeekSettingController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        $records = WeekSetting::query()->with('term')->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search')->toString().'%')->orWhere('note', 'like', '%'.$request->string('search')->toString().'%'))->orderBy('id')->paginate(15)->withQueryString();
        $week = $request->integer('edit') ? WeekSetting::findOrFail($request->integer('edit')) : null;
        $terms = TermSetting::query()->orderBy('id')->get();
        return view('admin.academics.curriculum.week-settings', compact('records', 'week', 'terms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:week,name'], 'description' => ['nullable', 'string', 'max:1000'], 'term_id' => ['required', 'exists:term,id'], 'start_date' => ['nullable', 'date'], 'end_date' => ['nullable', 'date', 'after_or_equal:start_date']]);
        WeekSetting::create(['name' => $data['name'], 'note' => $data['description'] ?? '', 'term_id' => $data['term_id'], 'start_date' => $data['start_date'] ?? null, 'end_date' => $data['end_date'] ?? null, 'is_holiday' => $request->boolean('is_holiday'), 'is_exam' => $request->boolean('is_exam'), 'is_active' => 'yes']);
        return to_route('admin.academics.week-settings.index')->with('success', 'Week added successfully.');
    }

    public function update(Request $request, WeekSetting $weekSetting)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', Rule::unique('week', 'name')->ignore($weekSetting->id)], 'description' => ['nullable', 'string', 'max:1000'], 'term_id' => ['required', 'exists:term,id'], 'start_date' => ['nullable', 'date'], 'end_date' => ['nullable', 'date', 'after_or_equal:start_date']]);
        $weekSetting->update(['name' => $data['name'], 'note' => $data['description'] ?? '', 'term_id' => $data['term_id'], 'start_date' => $data['start_date'] ?? null, 'end_date' => $data['end_date'] ?? null, 'is_holiday' => $request->boolean('is_holiday'), 'is_exam' => $request->boolean('is_exam'), 'is_active' => 'yes']);
        return to_route('admin.academics.week-settings.index')->with('success', 'Week updated successfully.');
    }

    public function destroy(WeekSetting $weekSetting)
    {
        $weekSetting->delete();
        return to_route('admin.academics.week-settings.index')->with('success', 'Week deleted successfully.');
    }
}
