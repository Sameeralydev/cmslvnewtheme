<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ParticleController extends Controller
{
    public static function menu(): array
    {
        $simple = [
            'area' => ['Area', 'area'], 'sections' => ['Sections', 'sections'], 'classes' => ['Class', 'classes'],
            'department' => ['Department', 'department'], 'designation' => ['Designation', 'designation'], 'skills' => ['Skill', 'skill'],
            'medium' => ['Medium', 'medium'], 'occupation' => ['Occupation', 'occupation'], 'living' => ['Living', 'living'],
            'education' => ['Education', 'education'], 'religion' => ['Religion', 'religion'], 'caste' => ['Caste', 'caste'],
            'banks' => ['Banks', 'banks'], 'concessiontype' => ['Concession Type', 'concessiontype'], 'previousschool' => ['Previous School', 'perviousschool'],
            'disablereason' => ['Disable Reason', 'disable_reason'], 'height' => ['Height', 'height'], 'weight' => ['Weight', 'weight'],
            'universityboard' => ['University / Board', 'universityboard'], 'degreecertificate' => ['Degree / Certificate', 'degreecertificate'],
            'institute' => ['Institute', 'institute'], 'training' => ['Training', 'training'], 'organization' => ['Organization', 'organization'],
            'jobcategory' => ['Job Category', 'job_categories'],
        ];
        $menu = [];
        foreach ($simple as $slug => [$label, $table]) $menu[$slug] = ['label' => $label, 'table' => $table, 'fields' => ['name', 'code', 'note', 'is_active']];
        $menu += [
            'province' => ['label' => 'Province', 'table' => 'province', 'fields' => ['name', 'code', 'country_id', 'note', 'is_active']],
            'division' => ['label' => 'Division', 'table' => 'division', 'fields' => ['name', 'code', 'country_id', 'province_id', 'note', 'is_active']],
            'district' => ['label' => 'City / District', 'table' => 'district', 'fields' => ['name', 'code', 'country_id', 'province_id', 'division_id', 'note', 'is_active']],
            'tehsils' => ['label' => 'Block / Town', 'table' => 'tehsils', 'fields' => ['name', 'code', 'country_id', 'province_id', 'division_id', 'district_id', 'note', 'is_active']],
            'academicyear' => ['label' => 'Academic Year', 'table' => 'adcademicyear', 'fields' => ['session', 'name', 'start_date', 'end_date', 'is_active']],
            'leavetype' => ['label' => 'Leave Type', 'table' => 'leave_types', 'fields' => ['type', 'name', 'note', 'is_active']],
            'studentcategories' => ['label' => 'Student Categories', 'table' => 'categories', 'fields' => ['category', 'name', 'note', 'is_active']],
        ];
        return $menu;
    }

    public function index(Request $request, string $slug): View
    {
        abort_unless(isset(self::menu()[$slug]), 404);
        $config = self::menu()[$slug];
        abort_unless(Schema::hasTable($config['table']), 404);
        $columns = Schema::getColumnListing($config['table']);
        $fields = array_values(array_intersect($config['fields'], $columns));
        $search = trim((string) $request->query('search', ''));
        $records = DB::table($config['table'])->when($search !== '', fn ($query) => $query->where(function ($query) use ($search, $fields): void {
            foreach ($fields as $field) $query->orWhere($field, 'like', "%{$search}%");
        }))->latest('id')->paginate(20)->withQueryString();
        $edit = $request->integer('edit') ? DB::table($config['table'])->where('id', $request->integer('edit'))->first() : null;
        return view('admin.systemsettings.particle', compact('slug', 'config', 'columns', 'fields', 'records', 'edit', 'search'));
    }

    public function store(Request $request, string $slug): RedirectResponse { return $this->save($request, $slug); }

    public function update(Request $request, string $slug, int $id): RedirectResponse { return $this->save($request, $slug, $id); }

    public function destroy(string $slug, int $id): RedirectResponse
    {
        abort_unless(isset(self::menu()[$slug]), 404);
        DB::table(self::menu()[$slug]['table'])->where('id', $id)->delete();
        return back()->with('success', self::menu()[$slug]['label'].' deleted successfully.');
    }

    private function save(Request $request, string $slug, ?int $id = null): RedirectResponse
    {
        abort_unless(isset(self::menu()[$slug]), 404);
        $config = self::menu()[$slug];
        $columns = Schema::getColumnListing($config['table']);
        $fields = array_values(array_intersect($config['fields'], $columns));
        $rules = [];
        if (in_array('name', $fields, true)) $rules['name'] = ['required', 'string', 'max:255'];
        $data = $request->validate($rules);
        foreach ($fields as $field) if ($request->has($field)) $data[$field] = $request->input($field) === '' ? null : $request->input($field);
        if (in_array('is_active', $fields, true) && !$request->has('is_active')) $data['is_active'] = 1;
        $stamp = array_intersect(['created_at', 'updated_at'], $columns);
        if ($id) DB::table($config['table'])->where('id', $id)->update($data + (in_array('updated_at', $stamp, true) ? ['updated_at' => now()] : []));
        else DB::table($config['table'])->insert($data + (in_array('created_at', $stamp, true) ? ['created_at' => now()] : []) + (in_array('updated_at', $stamp, true) ? ['updated_at' => now()] : []));
        return redirect()->route('admin.systemsettings.resource', ['slug' => $slug], false)->with('success', $config['label'].' saved successfully.');
    }
}
