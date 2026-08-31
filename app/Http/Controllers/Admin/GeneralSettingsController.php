<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GeneralSettingsController extends Controller
{
    public function edit(Request $request): View
    {
        $branchId = (int) ($request->integer('brc_id') ?: auth()->user()?->brc_id ?: DB::table('branch')->value('id'));
        $setting = DB::table('system_settings')->where('brc_id', $branchId)->first() ?? DB::table('system_settings')->first();
        $branch = DB::table('branch')->where('id', $branchId)->first() ?? DB::table('branch')->first();
        $session = $setting?->session_id ? DB::table('sessions')->where('id', $setting->session_id)->first() : DB::table('sessions')->latest('id')->first();

        $data = [
            'branchId' => $branchId,
            'setting' => $setting,
            'branch' => $branch,
            'session' => $session,
            'branches' => DB::table('branch')->orderBy('name')->get(),
            'countries' => DB::table('country')->orderBy('name')->get(['id', 'name']),
            'provinces' => DB::table('province')->where('country_id', $branch?->country_id)->orderBy('name')->get(['id', 'country_id', 'name']),
            'divisions' => DB::table('division')->where('province_id', $branch?->province_id)->orderBy('name')->get(['id', 'province_id', 'name']),
            'districts' => DB::table('district')->where('division_id', $branch?->division_id)->orderBy('name')->get(['id', 'division_id', 'name']),
            'tehsils' => DB::table('tehsils')->where('district_id', $branch?->district_id)->orderBy('name')->get(['id', 'district_id', 'name']),
            'areas' => DB::table('area')->where('tehsils_id', $branch?->tehsils_id)->orderBy('name')->get(['id', 'tehsils_id', 'name']),
            'sessions' => DB::table('sessions')->orderByDesc('id')->get(['id', 'session', 'start_date', 'end_date']),
            'currencies' => DB::table('currencies')->where('is_active', 1)->orderBy('name')->get(['id', 'name', 'short_name', 'symbol']),
        ];

        return view('admin.systemsettings.general', $data);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brc_id' => ['required', 'integer'], 'branch_name' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'integer'], 'province_id' => ['required', 'integer'],
            'division_id' => ['required', 'integer'], 'district_id' => ['required', 'integer'],
            'tehsils_id' => ['required', 'integer'], 'area_id' => ['required', 'integer'],
            'sch_name' => ['required', 'string', 'max:255'], 'sch_dise_code' => ['required', 'string', 'max:255'],
            'sch_phone' => ['required', 'string', 'max:255'], 'sch_email' => ['required', 'email', 'max:255'],
            'sch_address' => ['required', 'string'], 'sch_session_id' => ['required', 'integer'],
            'start_month' => ['required', 'date'], 'end_month' => ['required', 'date'],
            'sch_date_format' => ['required', 'string', 'max:50'], 'sch_timezone' => ['required', 'string', 'max:100'],
            'currency_id' => ['required', 'integer'], 'currency_format' => ['required', 'string', 'max:100'],
            'currency_place' => ['required', 'in:before_number,after_number'], 'base_url' => ['required', 'url'],
            'folder_path' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated): void {
            $branchId = $validated['brc_id'];
            $setting = DB::table('system_settings')->where('brc_id', $branchId)->first();
            $settingData = [
                'brc_id' => $branchId, 'session_id' => $validated['sch_session_id'], 'name' => $validated['sch_name'],
                'phone' => $validated['sch_phone'], 'dise_code' => $validated['sch_dise_code'], 'address' => $validated['sch_address'],
                'email' => $validated['sch_email'], 'timezone' => $validated['sch_timezone'], 'date_format' => $validated['sch_date_format'],
                'currency' => $validated['currency_id'], 'currency_format' => $validated['currency_format'], 'currency_place' => $validated['currency_place'],
                'base_url' => $validated['base_url'], 'folder_path' => $validated['folder_path'], 'updated_at' => now(),
            ];
            if ($setting) DB::table('system_settings')->where('id', $setting->id)->update($settingData);
            else DB::table('system_settings')->insert($settingData + ['created_at' => now()]);

            DB::table('branch')->where('id', $branchId)->update([
                'name' => $validated['branch_name'], 'country_id' => $validated['country_id'], 'province_id' => $validated['province_id'],
                'division_id' => $validated['division_id'], 'district_id' => $validated['district_id'], 'tehsils_id' => $validated['tehsils_id'],
                'area_id' => $validated['area_id'], 'updated_at' => now(),
            ]);
            DB::table('sessions')->where('id', $validated['sch_session_id'])->update([
                'start_date' => $validated['start_month'], 'end_date' => $validated['end_month'], 'updated_at' => now(),
            ]);
        });

        return back()->with('success', 'General settings updated successfully.');
    }
}
