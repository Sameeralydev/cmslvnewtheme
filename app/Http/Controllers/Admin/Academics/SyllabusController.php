<?php

namespace App\Http\Controllers\Admin\Academics;

use Dompdf\Dompdf;
use App\Models\Academics\Chapter;
use App\Models\Academics\Subject;
use App\Models\Academics\Syllabus;
use App\Models\Academics\TermSetting;
use App\Models\Academics\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SyllabusController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $branches = DB::table('branch')->whereIn('is_active', [1, '1', 'yes', 'active'])->orderBy('name')->get(['id', 'name']);
        $terms = TermSetting::query()->whereIn('is_active', [1, '1', 'yes', 'active'])->orderBy('id')->get(['id', 'name']);
        if ($terms->isEmpty()) $terms = TermSetting::query()->orderBy('id')->get(['id', 'name']);
        $classes = $this->syllabusClasses();

        $selected = $request->only(['brc_id', 'term_id', 'month_id', 'class_id', 'section_id', 'subject_id']);
        $sections = $selected['class_id'] ?? null ? $this->sections((int) $selected['class_id']) : collect();
        $subjects = $selected['class_id'] ?? null ? $this->subjects((int) $selected['class_id']) : collect();
        $records = collect();
        $syllabus = null;
        $chapters = collect();

        if ($request->boolean('searched')) {
            $request->validate([
                'brc_id' => ['required', 'exists:branch,id'],
                'term_id' => ['required', 'exists:term,id'],
                'month_id' => ['required', 'integer', 'between:1,12'],
                'class_id' => ['required', 'exists:classes,id'],
                'section_id' => ['required', 'exists:sections,id'],
                'subject_id' => ['required', 'exists:subjects,id'],
            ]);

            $records = DB::table('syllabus')
                ->leftJoin('branch', 'branch.id', '=', 'syllabus.brc_id')
                ->leftJoin('term', 'term.id', '=', 'syllabus.term_id')
                ->leftJoin('classes', 'classes.id', '=', 'syllabus.class_id')
                ->leftJoin('sections', 'sections.id', '=', 'syllabus.section_id')
                ->leftJoin('subjects', 'subjects.id', '=', 'syllabus.subject_id')
                ->where('syllabus.brc_id', $selected['brc_id'])
                ->where('syllabus.term_id', $selected['term_id'])
                ->where('syllabus.month_id', $selected['month_id'])
                ->where('syllabus.class_id', $selected['class_id'])
                ->where('syllabus.section_id', $selected['section_id'])
                ->where('syllabus.subject_id', $selected['subject_id'])
                ->orderByDesc('syllabus.id')
                ->get(['syllabus.*', 'branch.name as branch_name', 'term.name as term_name', 'classes.class as class_name', 'sections.section as section_name', 'subjects.name as subject_name']);
            $syllabus = $records->first();
            $chapters = $this->chapters((int) $selected['class_id'], (int) $selected['subject_id']);
        }

        return view('admin.academics.syllabus.index', compact('months', 'branches', 'terms', 'classes', 'sections', 'subjects', 'selected', 'records', 'syllabus', 'chapters'));
    }

    public function options(Request $request): JsonResponse
    {
        $classId = $request->integer('class_id');
        if ($classId < 1) {
            return response()->json(['sections' => [], 'subjects' => []]);
        }

        $subjectId = $request->integer('subject_id');
        return response()->json([
            'sections' => $this->sections($classId),
            'subjects' => $this->subjects($classId),
            'chapters' => $subjectId > 0 ? $this->chapters($classId, $subjectId) : [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'brc_id' => ['required', 'exists:branch,id'],
            'term_id' => ['required', 'exists:term,id'],
            'month_id' => ['required', 'integer', 'between:1,12'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'chapters_id' => ['nullable', 'array'],
            'chapters_id.*' => ['integer'],
            'topics_id' => ['nullable', 'array'],
            'topics_id.*' => ['integer'],
            'presentation' => ['nullable', 'string'],
            'vid_url' => ['nullable', 'url', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $data['chapters_id'] = json_encode(array_values($data['chapters_id'] ?? []));
        $data['topics_id'] = json_encode(array_values($data['topics_id'] ?? []));
        $data['status'] = 1;
        $data['created_by'] = auth()->id();
        unset($data['file']);

        if ($request->hasFile('file')) {
            $data['attachment'] = $request->file('file')->store('syllabus_attachment', 'public');
        }

        Syllabus::query()->create($data);
        $message = 'Syllabus saved successfully.';

        return redirect()->route('admin.academics.syllabus.index', [
            'searched' => 1, 'brc_id' => $request->brc_id, 'term_id' => $request->term_id,
            'month_id' => $request->month_id, 'class_id' => $request->class_id,
            'section_id' => $request->section_id, 'subject_id' => $request->subject_id,
        ])->with(['toast_type' => 'success', 'toast_message' => $message]);
    }

    public function destroy(int $syllabus): RedirectResponse
    {
        $record = Syllabus::query()->findOrFail($syllabus);
        if ($record->attachment) {
            Storage::disk('public')->delete($record->attachment);
        }
        $record->delete();

        return back()->with(['toast_type' => 'success', 'toast_message' => 'Syllabus deleted successfully.']);
    }

    public function pdf(Request $request)
    {
        $records = $this->filteredRecords($request);

        $html = view('admin.academics.syllabus.print', [
            'records' => $records,
            'months' => [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'],
        ])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();

        return response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="syllabus-directory.pdf"',
            'Content-Length' => (string) strlen($output),
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    public function print(Request $request): View
    {
        return view('admin.academics.syllabus.print', [
            'records' => $this->filteredRecords($request),
            'logo' => asset('assets/themes/default/images/logo.png'),
            'months' => [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'],
        ]);
    }

    public function directory(Request $request, ?int $branch = null): View
    {
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $branches = DB::table('branch')->whereIn('is_active', [1, '1', 'yes', 'active'])->orderBy('name')->get(['id', 'name']);
        $terms = TermSetting::query()->whereIn('is_active', [1, '1', 'yes', 'active'])->orderBy('id')->get(['id', 'name']);
        if ($terms->isEmpty()) $terms = TermSetting::query()->orderBy('id')->get(['id', 'name']);
        $classes = $this->syllabusClasses();
        $selected = array_merge(['brc_id' => $branch, 'term_id' => null, 'month_id' => null, 'class_id' => null, 'section_id' => null, 'subject_id' => null], $request->only(['brc_id', 'term_id', 'month_id', 'class_id', 'section_id', 'subject_id']));
        $sections = $selected['class_id'] ? $this->sections((int) $selected['class_id']) : collect();
        $subjects = $selected['class_id'] ? $this->subjects((int) $selected['class_id']) : collect();
        $records = collect();

        if ($request->boolean('searched')) {
            $request->validate([
                'brc_id' => ['required', 'exists:branch,id'], 'term_id' => ['required', 'exists:term,id'],
                'month_id' => ['required', 'integer', 'between:1,12'], 'class_id' => ['required', 'exists:classes,id'],
                'section_id' => ['required', 'exists:sections,id'], 'subject_id' => ['required', 'exists:subjects,id'],
            ]);
            $records = DB::table('syllabus')->leftJoin('branch', 'branch.id', '=', 'syllabus.brc_id')->leftJoin('term', 'term.id', '=', 'syllabus.term_id')->leftJoin('classes', 'classes.id', '=', 'syllabus.class_id')->leftJoin('sections', 'sections.id', '=', 'syllabus.section_id')->leftJoin('subjects', 'subjects.id', '=', 'syllabus.subject_id')->where('syllabus.brc_id', $selected['brc_id'])->where('syllabus.term_id', $selected['term_id'])->where('syllabus.month_id', $selected['month_id'])->where('syllabus.class_id', $selected['class_id'])->where('syllabus.section_id', $selected['section_id'])->where('syllabus.subject_id', $selected['subject_id'])->orderByDesc('syllabus.id')->get(['syllabus.*', 'branch.name as branch_name', 'term.name as term_name', 'classes.class as class_name', 'sections.section as section_name', 'subjects.name as subject_name']);
        }

        return view('admin.academics.syllabus.directory', compact('months', 'branches', 'terms', 'classes', 'sections', 'subjects', 'selected', 'records'));
    }

    private function sections(int $classId)
    {
        return DB::table('class_sections')
            ->join('sections', 'sections.id', '=', 'class_sections.section_id')
            ->where('class_sections.class_id', $classId)
            ->orderBy('sections.id')
            ->get(['sections.id', 'sections.section']);
    }

    private function syllabusClasses()
    {
        $order = ['Play Group', 'Nursery', 'Prep', 'One'];

        return DB::table('classes')
            ->whereIn('class', $order)
            ->get(['id', 'class'])
            ->sortBy(fn ($class) => array_search($class->class, $order, true))
            ->values();
    }

    private function subjects(int $classId)
    {
        return Subject::query()
            ->join('subject_group_subjects', 'subject_group_subjects.subject_id', '=', 'subjects.id')
            ->join('subject_groups', 'subject_groups.id', '=', 'subject_group_subjects.subject_group_id')
            ->where('subject_groups.class_id', $classId)
            ->orderBy('subjects.name')
            ->distinct()
            ->get(['subjects.id', 'subjects.name', 'subjects.code']);
    }

    private function chapters(int $classId, int $subjectId)
    {
        return Chapter::query()->where('class_id', $classId)->where('subject_id', $subjectId)
            ->orderBy('id')->get(['id', 'name', 'urdu as name_urdu'])->map(function ($chapter) use ($classId, $subjectId) {
                $chapter->topics = Topic::query()->where('class_id', $classId)->where('subject_id', $subjectId)
                    ->where('chapter_id', $chapter->id)->orderBy('id')->get(['id', 'name', 'urdu as name_urdu']);
                return $chapter;
            });
    }

    private function filteredRecords(Request $request)
    {
        $request->validate([
            'brc_id' => ['required', 'exists:branch,id'], 'term_id' => ['required', 'exists:term,id'],
            'month_id' => ['required', 'integer', 'between:1,12'], 'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'], 'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        return DB::table('syllabus')->leftJoin('branch', 'branch.id', '=', 'syllabus.brc_id')
            ->leftJoin('term', 'term.id', '=', 'syllabus.term_id')->leftJoin('classes', 'classes.id', '=', 'syllabus.class_id')
            ->leftJoin('sections', 'sections.id', '=', 'syllabus.section_id')->leftJoin('subjects', 'subjects.id', '=', 'syllabus.subject_id')
            ->where('syllabus.brc_id', $request->brc_id)->where('syllabus.term_id', $request->term_id)
            ->where('syllabus.month_id', $request->month_id)->where('syllabus.class_id', $request->class_id)
            ->where('syllabus.section_id', $request->section_id)->where('syllabus.subject_id', $request->subject_id)
            ->orderByDesc('syllabus.id')->get(['syllabus.*', 'branch.name as branch_name', 'term.name as term_name',
                'classes.class as class_name', 'sections.section as section_name', 'subjects.name as subject_name']);
    }

    private function makeSimplePdf(array $lines): string
    {
        $content = "BT\n/F1 11 Tf\n50 800 Td\n";
        foreach ($lines as $line) {
            $content .= '(' . str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], substr($line, 0, 125)) . ") Tj\n0 -16 Td\n";
        }
        $content .= "ET";
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Length ' . strlen($content) . " >>\nstream\n" . $content . "\nendstream",
        ];
        $pdf = "%PDF-1.4\n"; $offsets = [0];
        foreach ($objects as $number => $object) { $offsets[] = strlen($pdf); $pdf .= ($number + 1) . " 0 obj\n" . $object . "\nendobj\n"; }
        $xref = strlen($pdf); $pdf .= "xref\n0 6\n0000000000 65535 f \n";
        for ($i = 1; $i <= 5; $i++) { $pdf .= sprintf('%010d 00000 n \n', $offsets[$i]); }
        return $pdf . "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n" . $xref . "\n%%EOF";
    }
}
