<?php

namespace App\Http\Controllers\Admin\Account;

use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StudentFeeController extends BaseAccountController
{
    public function index(Request $request): View
    {
        return $this->feerevise($request);
    }

    /**
     * Resolve active branch ID.
     */
    protected function resolveBranchId(Request $request, ?int $branchId = null): int
    {
        if ($branchId && $branchId > 0) {
            return $branchId;
        }

        if ($request->filled('brc_id') && (int) $request->input('brc_id') > 0) {
            return (int) $request->input('brc_id');
        }

        if ($request->session()->has('brc_id')) {
            $sessId = (int) $request->session()->get('brc_id');
            if ($sessId > 0) {
                return $sessId;
            }
        }

        $user = $request->user();
        if ($user && !empty($user->brc_id)) {
            return (int) $user->brc_id;
        }

        return 1;
    }

    /**
     * Get system settings & session ID for branch.
     */
    protected function getBranchSettings(int $brc_id): object
    {
        $setting = null;
        if (Schema::hasTable('system_settings')) {
            $query = DB::table('system_settings')
                ->leftJoin('sessions', 'sessions.id', '=', 'system_settings.session_id')
                ->leftJoin('currencies', 'currencies.id', '=', 'system_settings.currency')
                ->where('system_settings.brc_id', $brc_id)
                ->select([
                    'system_settings.*',
                    'sessions.session as current_session_name',
                    'currencies.symbol as currency_symbol_text',
                ])
                ->first();

            if (!$query) {
                $query = DB::table('system_settings')
                    ->leftJoin('sessions', 'sessions.id', '=', 'system_settings.session_id')
                    ->leftJoin('currencies', 'currencies.id', '=', 'system_settings.currency')
                    ->select([
                        'system_settings.*',
                        'sessions.session as current_session_name',
                        'currencies.symbol as currency_symbol_text',
                    ])
                    ->first();
            }

            $setting = $query;
        }

        $sessionId = $setting->session_id ?? 1;
        $sessionName = $setting->current_session_name ?? (date('Y') . '-' . substr(date('Y') + 1, 2));
        $currencySymbol = $setting->currency_symbol ?? $setting->currency_symbol_text ?? 'Rs.';
        $dateFormat = $setting->date_format ?? 'Y-m-d';

        return (object) [
            'raw' => $setting,
            'session_id' => $sessionId,
            'session_name' => $sessionName,
            'currency_symbol' => $currencySymbol,
            'date_format' => $dateFormat,
        ];
    }

    /**
     * Convert any date format to Y-m-d.
     */
    protected function formatToYmd($dateStr, ?string $default = null): string
    {
        if (empty($dateStr)) {
            return $default ?: date('Y-m-d');
        }

        $dateStr = trim((string) $dateStr);

        if (strpos($dateStr, '/') !== false) {
            $parts = explode('/', $dateStr);
            if (count($parts) === 3) {
                if (strlen($parts[2]) === 4) {
                    return sprintf('%04d-%02d-%02d', (int) $parts[2], (int) $parts[1], (int) $parts[0]);
                } elseif (strlen($parts[0]) === 4) {
                    return sprintf('%04d-%02d-%02d', (int) $parts[0], (int) $parts[1], (int) $parts[2]);
                }
            }
        }

        $ts = strtotime(str_replace('/', '-', $dateStr));
        if ($ts !== false && $ts > 0) {
            return date('Y-m-d', $ts);
        }

        return $default ?: date('Y-m-d');
    }

    /**
     * Fee Revise Main Action: handles both GET and Search POST.
     */
    public function feerevise(Request $request, ?int $branch_id = null): View
    {
        $brc_id = $this->resolveBranchId($request, $branch_id);
        $settings = $this->getBranchSettings($brc_id);
        $session_id = $settings->session_id;

        $branchlist = Schema::hasTable('branches')
            ? Branch::query()->where('is_active', 'yes')->orWhere('is_active', '1')->orderBy('id', 'asc')->get()
            : (Schema::hasTable('branch') ? DB::table('branch')->orderBy('id', 'asc')->get() : collect());

        $classlist = Schema::hasTable('classes')
            ? DB::table('classes')->orderBy('id', 'asc')->get()
            : collect();

        $feetypeList = Schema::hasTable('accountshead')
            ? DB::table('accountshead')
                ->where('new_accounts_id', 19)
                ->where(function ($query) use ($brc_id) {
                    $query->whereNull('brc_id')->orWhere('brc_id', $brc_id);
                })
                ->orderBy('id', 'asc')
                ->get()
            : collect();

        $data = [
            'title' => 'Fee Revise',
            'brc_id' => $brc_id,
            'settings' => $settings,
            'branchlist' => $branchlist,
            'classlist' => $classlist,
            'feetypeList' => $feetypeList,
            'class_post' => '',
            'section_post' => '',
            'feesmanage' => '',
            'due_id' => '',
            'increment_type' => 1,
            'increment_amount' => '',
            'increment_value' => '',
            'school_amount' => '',
            'issue_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'dues_date' => date('Y-m-d'),
            'resultlist' => null,
        ];

        if ($request->isMethod('post')) {
            $class = $request->input('class_id');
            $section = $request->input('section_id');
            $feesmanage = $request->input('fees_manage');
            $due_id = $request->input('due_id');
            $increment_type = $request->input('is_increment_type', 1);
            $increment_amount = (float) $request->input('increment_amount', 0);
            $increment_value = (float) $request->input('increment_value', 0);
            $school_amount = $request->input('school_amount');
            $issue_date = $request->input('issue_date', date('Y-m-d'));
            $due_date = $request->input('due_date', date('Y-m-d'));
            $dues_date = $request->input('dues_date', date('Y-m-d'));

            $data['class_post'] = $class;
            $data['section_post'] = $section;
            $data['feesmanage'] = $feesmanage;
            $data['due_id'] = $due_id;
            $data['increment_type'] = $increment_type;
            $data['increment_amount'] = $increment_amount;
            $data['increment_value'] = $increment_value;
            $data['school_amount'] = $school_amount;
            $data['issue_date'] = $issue_date;
            $data['due_date'] = $due_date;
            $data['dues_date'] = $dues_date;

            // Search active students for branch, session, class, and section
            $query = DB::table('students')
                ->join('student_session', 'student_session.student_id', '=', 'students.id')
                ->leftJoin('classes', 'student_session.class_id', '=', 'classes.id')
                ->leftJoin('sections', 'student_session.section_id', '=', 'sections.id')
                ->where('student_session.brc_id', $brc_id)
                ->where('student_session.session_id', $session_id)
                ->where('students.is_active', 'yes')
                ->select([
                    'students.id',
                    'students.admission_no',
                    'students.firstname',
                    'students.lastname',
                    'students.father_name',
                    'students.dob',
                    'students.gender',
                    'student_session.id as student_session_id',
                    'student_session.brc_id',
                    'classes.class',
                    'sections.section',
                ]);

            if (!empty($class)) {
                $query->where('student_session.class_id', $class);
            }
            if (!empty($section)) {
                $query->where('student_session.section_id', $section);
            }

            $students = $query->orderBy('classes.id', 'asc')
                ->orderBy('sections.id', 'asc')
                ->orderBy('students.firstname', 'asc')
                ->get();

            // Enrich each student with fee details for due_id
            foreach ($students as $std) {
                $feeAssign = DB::table('student_fees_assign')
                    ->where('student_session_id', $std->student_session_id)
                    ->where('feetype_id', $due_id)
                    ->first();

                $currentAmount = $feeAssign ? (float) $feeAssign->current_amount : 0.00;
                $std->current_fee = $currentAmount;

                // Calculate suggested revised fee
                if ($feesmanage == 1) { // Increment
                    if ($increment_type == 1) {
                        $std->suggested_fee = $currentAmount > 0 ? ($currentAmount + $increment_amount) : 0;
                    } else {
                        $std->suggested_fee = $currentAmount > 0 ? ($currentAmount + (($currentAmount * $increment_value) / 100)) : 0;
                    }
                } elseif ($feesmanage == 2) { // Decrement
                    $std->suggested_fee = '';
                } elseif ($feesmanage == 3) { // Assign Fee
                    $classFee = DB::table('fee_groups_feetype')
                        ->where('fee_class_id', $class)
                        ->where('feetype_id', $due_id)
                        ->where('brc_id', $brc_id)
                        ->first();
                    $std->suggested_fee = $classFee ? (float) $classFee->amount : $currentAmount;
                } else {
                    $std->suggested_fee = '';
                }
            }

            $data['resultlist'] = $students;
        }

        return view('admin.account.studentfee.fee_revise', $data);
    }

    /**
     * Fee Revise AJAX Update: saves fee revision for checked students.
     */
    public function feereviseUpdate(Request $request): JsonResponse
    {
        $checkedStudents = $request->input('check', []);
        $feesmanage = (int) $request->input('feesmanage');
        $userId = $request->user() ? $request->user()->id : 1;

        if (empty($checkedStudents)) {
            return response()->json([
                'status' => 'fail',
                'error' => ['check' => 'Please select at least one student.'],
            ]);
        }

        DB::beginTransaction();
        try {
            foreach ($checkedStudents as $studentSessionId) {
                $studentSessionId = (int) $studentSessionId;
                $duesId = (int) $request->input('dues_id_' . $studentSessionId);

                $stdSession = DB::table('student_session')
                    ->join('students', 'students.id', '=', 'student_session.student_id')
                    ->where('student_session.id', $studentSessionId)
                    ->select('student_session.*', 'students.id as student_id')
                    ->first();

                if (!$stdSession) {
                    continue;
                }

                $brcId = $stdSession->brc_id;
                $classId = $stdSession->class_id;

                if ($feesmanage === 1) { // Increment
                    $incrementFee = (float) $request->input('incrementfee_' . $studentSessionId, 0);
                    $existingAssign = DB::table('student_fees_assign')
                        ->where('student_session_id', $studentSessionId)
                        ->where('feetype_id', $duesId)
                        ->first();

                    if ($existingAssign) {
                        $feeAmount = (float) $existingAssign->fee_amount;
                        $currentAmount = $incrementFee > 0 ? $incrementFee : (float) $existingAssign->current_amount;
                        $discountAmount = $feeAmount - $currentAmount;

                        DB::table('student_fees_assign')
                            ->where('id', $existingAssign->id)
                            ->update([
                                'fee_amount' => $feeAmount,
                                'discount_amount' => $discountAmount,
                                'current_amount' => $currentAmount,
                            ]);
                    } else {
                        $classFee = DB::table('fee_groups_feetype')
                            ->where('fee_class_id', $classId)
                            ->where('feetype_id', $duesId)
                            ->where('brc_id', $brcId)
                            ->first();

                        $feeAmount = $classFee ? (float) $classFee->amount : $incrementFee;
                        $currentAmount = $incrementFee;
                        $discountAmount = $feeAmount - $currentAmount;
                        $frequency = $classFee ? $classFee->frequency : 'Monthly';

                        DB::table('student_fees_assign')->insert([
                            'brc_id' => $brcId,
                            'student_id' => $stdSession->student_id,
                            'student_session_id' => $studentSessionId,
                            'feetype_id' => $duesId,
                            'frequency' => $frequency,
                            'fee_amount' => $feeAmount,
                            'discount_amount' => $discountAmount,
                            'current_amount' => $currentAmount,
                            'created_by' => $userId,
                        ]);
                    }
                } elseif ($feesmanage === 2) { // Decrement
                    $decrementFee = (float) $request->input('decrementfee_' . $studentSessionId, 0);
                    $existingAssign = DB::table('student_fees_assign')
                        ->where('student_session_id', $studentSessionId)
                        ->where('feetype_id', $duesId)
                        ->first();

                    if ($existingAssign) {
                        $feeAmount = (float) $existingAssign->fee_amount;
                        $currentAmount = max(0, (float) $existingAssign->current_amount - $decrementFee);
                        $discountAmount = $feeAmount - $currentAmount;

                        DB::table('student_fees_assign')
                            ->where('id', $existingAssign->id)
                            ->update([
                                'fee_amount' => $feeAmount,
                                'discount_amount' => $discountAmount,
                                'current_amount' => $currentAmount,
                            ]);
                    }
                } elseif ($feesmanage === 3) { // Assign Fee
                    $assignFee = (float) $request->input('assignfee_' . $studentSessionId, 0);
                    $existingAssign = DB::table('student_fees_assign')
                        ->where('student_session_id', $studentSessionId)
                        ->where('feetype_id', $duesId)
                        ->first();

                    if ($existingAssign) {
                        $feeAmount = (float) $existingAssign->fee_amount;
                        $currentAmount = $assignFee;
                        $discountAmount = $feeAmount - $currentAmount;

                        DB::table('student_fees_assign')
                            ->where('id', $existingAssign->id)
                            ->update([
                                'fee_amount' => $feeAmount,
                                'discount_amount' => $discountAmount,
                                'current_amount' => $currentAmount,
                            ]);
                    } else {
                        $classFee = DB::table('fee_groups_feetype')
                            ->where('fee_class_id', $classId)
                            ->where('feetype_id', $duesId)
                            ->where('brc_id', $brcId)
                            ->first();

                        $feeAmount = $classFee ? (float) $classFee->amount : $assignFee;
                        $currentAmount = $assignFee;
                        $discountAmount = $feeAmount - $currentAmount;
                        $frequency = $classFee ? $classFee->frequency : 'Monthly';

                        DB::table('student_fees_assign')->insert([
                            'brc_id' => $brcId,
                            'student_id' => $stdSession->student_id,
                            'student_session_id' => $studentSessionId,
                            'feetype_id' => $duesId,
                            'frequency' => $frequency,
                            'fee_amount' => $feeAmount,
                            'discount_amount' => $discountAmount,
                            'current_amount' => $currentAmount,
                            'created_by' => $userId,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Fees updated successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'fail',
                'error' => ['save' => 'Error revising fee: ' . $e->getMessage()],
            ]);
        }
    }

    /**
     * AJAX: Get Sections by Class ID.
     */
    public function getSectionsByClass(Request $request, $class_id): JsonResponse
    {
        $classId = (int) $class_id;
        $sections = collect();

        if (Schema::hasTable('class_sections')) {
            $sections = DB::table('class_sections')
                ->join('sections', 'sections.id', '=', 'class_sections.section_id')
                ->where('class_sections.class_id', $classId)
                ->select('sections.id as section_id', 'sections.id', 'sections.section', 'sections.section as name')
                ->orderBy('sections.section', 'asc')
                ->get();
        }

        if ($sections->isEmpty() && Schema::hasTable('student_session')) {
            $sections = DB::table('student_session')
                ->join('sections', 'sections.id', '=', 'student_session.section_id')
                ->where('student_session.class_id', $classId)
                ->select('sections.id as section_id', 'sections.id', 'sections.section', 'sections.section as name')
                ->distinct()
                ->orderBy('sections.section', 'asc')
                ->get();
        }

        if ($sections->isEmpty() && Schema::hasTable('sections')) {
            $sections = DB::table('sections')
                ->select('sections.id as section_id', 'sections.id', 'sections.section', 'sections.section as name')
                ->orderBy('sections.section', 'asc')
                ->get();
        }

        return response()->json($sections);
    }
}
