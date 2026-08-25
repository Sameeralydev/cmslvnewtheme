<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Models\Hrms\InterviewRating;
use App\Models\Hrms\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterviewRatingController extends Controller
{
    private const DECISIONS = ['pending', 'recommended', 'not_recommended'];
    private const RATING_FIELDS = [
        'appearance_rating', 'communication_rating', 'reasoning_rating', 'education_rating',
        'job_knowledge_rating', 'work_experience_rating', 'general_knowledge_rating',
        'iq_level_rating', 'pose_maturity_rating', 'personality_rating',
    ];

    public function index(): View
    {
        return view('admin.hrms.interviewratings.index', $this->pageData());
    }

    public function store(Request $request): RedirectResponse
    {
        InterviewRating::create($this->validatedData($request));
        return to_route('admin.hrms.interviewratings.index')->with('success', 'Interview rating created successfully.');
    }

    public function edit(int $interviewRating): View
    {
        return view('admin.hrms.interviewratings.index', $this->pageData(InterviewRating::findOrFail($interviewRating)));
    }

    public function update(Request $request, int $interviewRating): RedirectResponse
    {
        InterviewRating::findOrFail($interviewRating)->update($this->validatedData($request));
        return to_route('admin.hrms.interviewratings.index')->with('success', 'Interview rating updated successfully.');
    }

    public function show(int $interviewRating): View
    {
        return view('admin.hrms.interviewratings.index', $this->pageData(InterviewRating::findOrFail($interviewRating), true));
    }

    public function destroy(int $interviewRating): RedirectResponse
    {
        InterviewRating::findOrFail($interviewRating)->delete();
        return to_route('admin.hrms.interviewratings.index')->with('success', 'Interview rating deleted successfully.');
    }

    public function updateDecision(Request $request, int $interviewRating): JsonResponse
    {
        $data = $request->validate(['decision' => ['required', 'in:' . implode(',', self::DECISIONS)]]);
        InterviewRating::findOrFail($interviewRating)->update(['final_decision' => $data['decision']]);
        return response()->json(['success' => true]);
    }

    private function pageData(?InterviewRating $rating = null, bool $viewMode = false): array
    {
        $candidates = JobApplication::query()->where('status', 'Approved')->orderBy('full_name')->get();
        if ($rating && !$candidates->contains(fn ($candidate) => $candidate->full_name === $rating->candidate_name)) {
            $candidates->push((object) ['full_name' => $rating->candidate_name, 'position_applied' => $rating->position_applied, 'min_salary_acceptable' => '']);
        }
        return [
            'ratings' => InterviewRating::query()->latest('created_at')->get(),
            'rating' => $rating,
            'candidates' => $candidates,
            'viewMode' => $viewMode,
            'decisions' => self::DECISIONS,
            'ratingFields' => self::RATING_FIELDS,
            'today' => today()->toDateString(),
        ];
    }

    private function validatedData(Request $request): array
    {
        $rules = [
            'candidate_name' => ['required', 'string', 'max:255'],
            'interview_date' => ['required', 'date'],
            'position_applied' => ['required', 'string', 'max:255'],
            'salary_expectation' => ['nullable', 'string', 'max:255'],
            'final_decision' => ['nullable', 'in:' . implode(',', self::DECISIONS)],
        ];
        foreach (self::RATING_FIELDS as $field) $rules[$field] = ['nullable', 'integer', 'min:0', 'max:10'];
        $data = $request->validate($rules);
        $total = 0;
        foreach (self::RATING_FIELDS as $field) { $data[$field] = (int) ($data[$field] ?? 0); $total += $data[$field]; }
        $data['total_points'] = $total;
        $data['final_decision'] = $data['final_decision'] ?? 'pending';
        return $data;
    }
}
