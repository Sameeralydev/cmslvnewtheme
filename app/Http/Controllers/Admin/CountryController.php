<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $countries = DB::table('country')->when($search !== '', fn ($query) => $query->where(function ($query) use ($search): void {
            $query->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        }))->orderBy('name')->paginate(20)->withQueryString();
        $countries->getCollection()->transform(function ($country) {
            $country->languageNames = collect(preg_split('/\s*,\s*/', (string) $country->languages, -1, PREG_SPLIT_NO_EMPTY))
                ->map(fn (string $language): string => class_exists('Locale') ? (\Locale::getDisplayLanguage($language, 'en') ?: $language) : $language)
                ->implode(', ');
            return $country;
        });
        $edit = $request->integer('edit') ? DB::table('country')->where('id', $request->integer('edit'))->first() : null;

        return view('admin.systemsettings.country', compact('countries', 'edit', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        DB::table('country')->insert($data + ['is_active' => 'yes', 'created_at' => now()]);
        return back()->with('success', 'Country added successfully.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $this->validated($request, $id);
        DB::table('country')->where('id', $id)->update($data + ['updated_by' => auth()->id(), 'updated_at' => now()]);
        return redirect()->route('admin.systemsettings.country.index', absolute: false)->with('success', 'Country updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        DB::table('country')->where('id', $id)->delete();
        return back()->with('success', 'Country deleted successfully.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $request->validate(['name' => ['required', 'string', 'max:255', 'unique:country,name,'.($id ?: 'NULL').',id'], 'code' => ['nullable', 'string', 'max:20'], 'currencyCode' => ['nullable', 'string', 'max:20'], 'languages' => ['nullable', 'string', 'max:255'], 'telephonePrefix' => ['nullable', 'string', 'max:30'], 'description' => ['nullable', 'string']]);
        return ['name' => $request->string('name')->trim()->toString(), 'code' => $request->string('code')->trim()->toString() ?: null, 'currencyCode' => $request->string('currencyCode')->trim()->toString() ?: null, 'languages' => $request->string('languages')->trim()->toString() ?: null, 'telephonePrefix' => $request->string('telephonePrefix')->trim()->toString() ?: null, 'note' => $request->string('description')->trim()->toString() ?: null];
    }
}
