<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Models\Hrms\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WrittenTestController extends Controller
{
    public function index(): View
    {
        return view('admin.hrms.writtentest.index', [
            'candidates' => JobApplication::query()->where('status', 'Approved')->orderBy('id')->get(),
        ]);
    }

    public function updateMarks(Request $request, int $jobApplication): JsonResponse
    {
        $data = $request->validate([
            'marks' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'gt:0'],
        ]);

        if ((float) $data['marks'] > (float) $data['total']) {
            return response()->json(['success' => false, 'message' => 'Marks cannot exceed total marks.'], 422);
        }

        JobApplication::query()->whereKey($jobApplication)->where('status', 'Approved')->update([
            'written_test_marks' => $data['marks'],
            'written_test_total' => $data['total'],
        ]);

        return response()->json(['success' => true]);
    }
}
