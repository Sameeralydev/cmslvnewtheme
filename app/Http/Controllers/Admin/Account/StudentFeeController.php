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

    /**
     * Assign Dues Main View Action.
     */
    public function assigndues(Request $request, ?int $branch_id = null): View
    {
        $brc_id = $this->resolveBranchId($request, $branch_id);
        $settings = $this->getBranchSettings($brc_id);

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
                ->select(['id', 'name as type', 'code'])
                ->orderBy('id', 'asc')
                ->get()
            : collect();

        $sectionlist = Schema::hasTable('sections')
            ? DB::table('sections')->orderBy('id', 'asc')->get()
            : collect();

        $data = [
            'title' => 'Assign Dues',
            'brc_id' => $brc_id,
            'settings' => $settings,
            'branchlist' => $branchlist,
            'classlist' => $classlist,
            'sectionlist' => $sectionlist,
            'feetypeList' => $feetypeList,
        ];

        return view('admin.account.studentfee.assign_dues', $data);
    }

    /**
     * AJAX: Get Students by Branch for Assign Dues.
     */
    public function getStudentByBranch(Request $request): JsonResponse
    {
        $brc_id = (int) $request->input('brc_id');
        $settings = $this->getBranchSettings($brc_id);
        $session_id = $settings->session_id;

        $branch = DB::table('branches')->where('id', $brc_id)->first();
        if (!$branch && Schema::hasTable('branch')) {
            $branch = DB::table('branch')->where('id', $brc_id)->first();
        }

        $totalStudents = DB::table('student_session')
            ->join('students', 'students.id', '=', 'student_session.student_id')
            ->where('student_session.brc_id', $brc_id)
            ->where('student_session.session_id', $session_id)
            ->where('students.is_active', 'yes')
            ->count();

        $feetype = DB::table('accountshead')
            ->where('new_accounts_id', 19)
            ->where(function ($query) use ($brc_id) {
                $query->whereNull('brc_id')->orWhere('brc_id', $brc_id);
            })
            ->select(['id', 'name as type'])
            ->get();

        return response()->json([
            'student' => [
                'brc_id' => $brc_id,
                'branch_name' => $branch->name ?? 'Branch ' . $brc_id,
                'total_student' => $totalStudents,
            ],
            'feetype' => $feetype,
        ]);
    }

    /**
     * AJAX: Get Classes by Branch for Assign Dues.
     */
    public function getClassesByBranch(Request $request): JsonResponse
    {
        $brc_id = (int) ($request->input('brc_id') ?: $this->resolveBranchId($request));
        $class_id = $request->input('class_id');
        $settings = $this->getBranchSettings($brc_id);
        $session_id = $settings->session_id;

        $classesQuery = DB::table('classes');
        if (!empty($class_id) && $class_id !== 'all' && is_numeric($class_id)) {
            $classesQuery->where('id', (int) $class_id);
        }
        $classes = $classesQuery->orderBy('id', 'asc')->get();
        $studentData = [];

        foreach ($classes as $cls) {
            $count = DB::table('student_session')
                ->join('students', 'students.id', '=', 'student_session.student_id')
                ->when($brc_id > 0, fn ($q) => $q->where('student_session.brc_id', $brc_id))
                ->where('student_session.session_id', $session_id)
                ->where('student_session.class_id', $cls->id)
                ->where('students.is_active', 'yes')
                ->count();

            if ($count > 0) {
                $studentData[] = [
                    'id' => $cls->id,
                    'classname' => $cls->class,
                    'classesstudent' => [$cls->id => $count],
                    'strength' => $count,
                ];
            }
        }

        $feetype = DB::table('accountshead')
            ->where('new_accounts_id', 19)
            ->where(function ($query) use ($brc_id) {
                $query->whereNull('brc_id')->orWhere('brc_id', $brc_id);
            })
            ->select(['id', 'name as type'])
            ->get();

        if (empty($studentData)) {
            return response()->json(['status' => 'fail', 'feetype' => $feetype]);
        }

        return response()->json([
            'student' => $studentData,
            'feetype' => $feetype,
        ]);
    }

    /**
     * AJAX: Get Class Sections by Branch for Assign Dues.
     */
    public function getClassesSectionsByBranch(Request $request): JsonResponse
    {
        $brc_id = (int) ($request->input('brc_id') ?: $this->resolveBranchId($request));
        $section_id = $request->input('section_id');
        $settings = $this->getBranchSettings($brc_id);
        $session_id = $settings->session_id;

        $classSectionsQuery = DB::table('class_sections')
            ->join('classes', 'classes.id', '=', 'class_sections.class_id')
            ->join('sections', 'sections.id', '=', 'class_sections.section_id');

        if (!empty($section_id) && $section_id !== 'all' && is_numeric($section_id)) {
            $classSectionsQuery->where('class_sections.section_id', (int) $section_id);
        }

        $classSections = $classSectionsQuery
            ->select([
                'class_sections.class_id',
                'class_sections.section_id',
                'classes.class as classname',
                'sections.section as sectionname',
            ])
            ->orderBy('classes.id', 'asc')
            ->orderBy('sections.id', 'asc')
            ->get();

        $studentData = [];

        foreach ($classSections as $cs) {
            $count = DB::table('student_session')
                ->join('students', 'students.id', '=', 'student_session.student_id')
                ->when($brc_id > 0, fn ($q) => $q->where('student_session.brc_id', $brc_id))
                ->where('student_session.session_id', $session_id)
                ->where('student_session.class_id', $cs->class_id)
                ->where('student_session.section_id', $cs->section_id)
                ->where('students.is_active', 'yes')
                ->count();

            if ($count > 0) {
                $studentData[] = [
                    'class_id' => $cs->class_id,
                    'section_id' => $cs->section_id,
                    'classname' => $cs->classname,
                    'sectionname' => $cs->sectionname,
                    'totalstudent' => [$cs->section_id => $count],
                    'strength' => $count,
                ];
            }
        }

        $feetype = DB::table('accountshead')
            ->where('new_accounts_id', 19)
            ->where(function ($query) use ($brc_id) {
                $query->whereNull('brc_id')->orWhere('brc_id', $brc_id);
            })
            ->select(['id', 'name as type'])
            ->get();

        if (empty($studentData)) {
            return response()->json(['status' => 'fail', 'feetype' => $feetype]);
        }

        return response()->json([
            'student' => $studentData,
            'feetype' => $feetype,
        ]);
    }

    /**
     * AJAX: Get Students by Class & Section for Assign Dues.
     */
    public function getStudentClassSectionsByBranch(Request $request): JsonResponse
    {
        $brc_id = (int) ($request->input('brc_id') ?: $this->resolveBranchId($request));
        $class_id = (int) $request->input('class_id');
        $section_id = (int) $request->input('section_id');
        $settings = $this->getBranchSettings($brc_id);
        $session_id = $settings->session_id;

        $query = DB::table('students')
            ->join('student_session', 'student_session.student_id', '=', 'students.id')
            ->when($brc_id > 0, fn ($q) => $q->where('student_session.brc_id', $brc_id))
            ->where('student_session.session_id', $session_id)
            ->where('students.is_active', 'yes');

        if ($class_id > 0) {
            $query->where('student_session.class_id', $class_id);
        }

        if ($section_id > 0) {
            $query->where(function ($q) use ($section_id) {
                $q->where('student_session.section_id', $section_id)
                    ->orWhereNull('student_session.section_id')
                    ->orWhere('student_session.section_id', 0);
            });
        }

        $students = $query
            ->select([
                'student_session.id as student_session_id',
                'students.admission_no',
                'students.firstname',
                'students.lastname',
                'students.father_name',
            ])
            ->orderBy('students.firstname', 'asc')
            ->get();

        if ($students->isEmpty() && $class_id > 0) {
            $students = DB::table('students')
                ->join('student_session', 'student_session.student_id', '=', 'students.id')
                ->when($brc_id > 0, fn ($q) => $q->where('student_session.brc_id', $brc_id))
                ->where('student_session.session_id', $session_id)
                ->where('student_session.class_id', $class_id)
                ->where('students.is_active', 'yes')
                ->select([
                    'student_session.id as student_session_id',
                    'students.admission_no',
                    'students.firstname',
                    'students.lastname',
                    'students.father_name',
                ])
                ->orderBy('students.firstname', 'asc')
                ->get();
        }

        $feetype = DB::table('accountshead')
            ->where('new_accounts_id', 19)
            ->where(function ($query) use ($brc_id) {
                $query->whereNull('brc_id')->orWhere('brc_id', $brc_id);
            })
            ->select(['id', 'name as type'])
            ->get();

        if ($students->isEmpty()) {
            return response()->json(['status' => 'fail', 'feetype' => $feetype]);
        }

        return response()->json([
            'student' => $students,
            'feetype' => $feetype,
        ]);
    }

    /**
     * AJAX: Get Students by Admission No for Assign Dues.
     */
    public function getstdByBrcIDByAdmitNo(Request $request): JsonResponse
    {
        $brc_id = (int) $request->input('brc_id');
        $admit_no = trim((string) $request->input('admit_no'));
        $settings = $this->getBranchSettings($brc_id);
        $session_id = $settings->session_id;

        $admitArray = array_filter(array_map('trim', explode(',', $admit_no)));

        $students = DB::table('students')
            ->join('student_session', 'student_session.student_id', '=', 'students.id')
            ->where('student_session.brc_id', $brc_id)
            ->where('student_session.session_id', $session_id)
            ->whereIn('students.admission_no', $admitArray)
            ->where('students.is_active', 'yes')
            ->select([
                'student_session.id as student_session_id',
                'students.admission_no',
                'students.firstname',
                'students.lastname',
                'students.father_name',
            ])
            ->get();

        $feetype = DB::table('accountshead')
            ->where('new_accounts_id', 19)
            ->where(function ($query) use ($brc_id) {
                $query->whereNull('brc_id')->orWhere('brc_id', $brc_id);
            })
            ->select(['id', 'name as type'])
            ->get();

        if ($students->isEmpty()) {
            return response()->json(['status' => 'fail', 'feetype' => $feetype]);
        }

        return response()->json([
            'student' => $students,
            'feetype' => $feetype,
        ]);
    }

    /**
     * AJAX: Get Fee Types by Branch ID.
     */
    public function getFeeTypeByBranchID(Request $request): JsonResponse
    {
        $brc_id = (int) ($request->input('brc_id') ?? $request->input('camp_id') ?? 1);
        $feetype = DB::table('accountshead')
            ->where('new_accounts_id', 19)
            ->where(function ($query) use ($brc_id) {
                $query->whereNull('brc_id')->orWhere('brc_id', $brc_id);
            })
            ->select(['id', 'name as type'])
            ->get();

        return response()->json($feetype);
    }

    /**
     * AJAX: Process and Save Assign Dues.
     */
    public function addDues(Request $request): JsonResponse
    {
        $duesTypes = $request->input('dues_type', []);
        $duesAmounts = $request->input('dues_amount', []);
        $schoolAmounts = $request->input('school_amount', []);
        $issueDate = $this->formatToYmd($request->input('issue_date', date('d/m/Y')));
        $dueDate = $this->formatToYmd($request->input('due_date', date('d/m/Y')));
        $duesDate = $this->formatToYmd($request->input('dues_date', date('d/m/Y')));
        $description = $request->input('description', '');
        $categorySelect = $request->input('selectproceed');
        $userId = $request->user() ? $request->user()->id : 1;

        if (empty($categorySelect)) {
            if ($request->has('students_session_id')) {
                $categorySelect = 'students';
            } elseif ($request->has('class_id')) {
                $categorySelect = 'classes';
            } elseif ($request->has('selec_barch')) {
                $categorySelect = 'branch';
            } else {
                $categorySelect = 'branch';
            }
        }

        // Collect student session IDs based on selection
        $studentSessionIds = [];
        $brc_id = (int) ($request->input('selec_barch') ?: ($request->input('select_brc_id') ?: ($request->input('sec_select_brc_id') ?: ($request->input('sw_brc_id') ?: $this->resolveBranchId($request)))));
        $settings = $this->getBranchSettings($brc_id);
        $session_id = $settings->session_id;

        if ($categorySelect === 'branch') {
            $studentSessionIds = DB::table('student_session')
                ->join('students', 'students.id', '=', 'student_session.student_id')
                ->when($brc_id > 0, fn ($q) => $q->where('student_session.brc_id', $brc_id))
                ->where('student_session.session_id', $session_id)
                ->where('students.is_active', 'yes')
                ->pluck('student_session.id')
                ->toArray();
        } elseif ($categorySelect === 'classes') {
            $classIds = (array) $request->input('class_id', []);
            $query = DB::table('student_session')
                ->join('students', 'students.id', '=', 'student_session.student_id')
                ->when($brc_id > 0, fn ($q) => $q->where('student_session.brc_id', $brc_id))
                ->where('student_session.session_id', $session_id)
                ->where('students.is_active', 'yes');

            if (!empty($classIds)) {
                $query->whereIn('student_session.class_id', $classIds);
            }

            $studentSessionIds = $query->pluck('student_session.id')->toArray();
        } elseif ($categorySelect === 'sections') {
            $classIds = (array) $request->input('class_id', []);
            $sectionIds = (array) $request->input('section_id', []);
            $query = DB::table('student_session')
                ->join('students', 'students.id', '=', 'student_session.student_id')
                ->when($brc_id > 0, fn ($q) => $q->where('student_session.brc_id', $brc_id))
                ->where('student_session.session_id', $session_id)
                ->where('students.is_active', 'yes');

            if (!empty($classIds)) {
                $query->whereIn('student_session.class_id', $classIds);
            }
            if (!empty($sectionIds)) {
                $query->whereIn('student_session.section_id', $sectionIds);
            }

            $studentSessionIds = $query->pluck('student_session.id')->toArray();
        } elseif ($categorySelect === 'students') {
            $studentSessionIds = (array) $request->input('students_session_id', []);

            if (empty($studentSessionIds)) {
                $admit_no = trim((string) ($request->input('sw_admission_no') ?: $request->input('admission_no', '')));
                if (!empty($admit_no)) {
                    $admitArray = array_filter(array_map('trim', explode(',', $admit_no)));
                    $studentSessionIds = DB::table('student_session')
                        ->join('students', 'students.id', '=', 'student_session.student_id')
                        ->when($brc_id > 0, fn ($q) => $q->where('student_session.brc_id', $brc_id))
                        ->where('student_session.session_id', $session_id)
                        ->whereIn('students.admission_no', $admitArray)
                        ->where('students.is_active', 'yes')
                        ->pluck('student_session.id')
                        ->toArray();
                } else {
                    $class_id = (int) $request->input('sw_class_id');
                    $section_id = (int) $request->input('sw_section_id');
                    $query = DB::table('student_session')
                        ->join('students', 'students.id', '=', 'student_session.student_id')
                        ->when($brc_id > 0, fn ($q) => $q->where('student_session.brc_id', $brc_id))
                        ->where('student_session.session_id', $session_id)
                        ->where('students.is_active', 'yes');

                    if ($class_id > 0) $query->where('student_session.class_id', $class_id);
                    if ($section_id > 0) $query->where('student_session.section_id', $section_id);

                    $studentSessionIds = $query->pluck('student_session.id')->toArray();
                }
            }
        }

        if (empty($studentSessionIds)) {
            return response()->json([
                'status' => 'fail',
                'error' => ['selectproceed' => 'No active students selected for dues.'],
            ]);
        }

        DB::beginTransaction();
        try {
            foreach ($studentSessionIds as $studentSessionId) {
                $stdSession = DB::table('student_session')
                    ->where('id', $studentSessionId)
                    ->first();

                if (!$stdSession) {
                    continue;
                }

                $studentBrcId = $stdSession->brc_id;
                $settings = $this->getBranchSettings($studentBrcId);

                foreach ($duesTypes as $k => $duesTypeId) {
                    $amount = isset($duesAmounts[$k]) ? (float) $duesAmounts[$k] : 0;
                    $schoolAmt = isset($schoolAmounts[$k]) ? (float) $schoolAmounts[$k] : $amount;

                    if ($amount <= 0) {
                        continue;
                    }

                    if (Schema::hasTable('student_fees_deposite_details')) {
                        DB::table('student_fees_deposite_details')->insert([
                            'brc_id' => $studentBrcId,
                            'student_id' => $stdSession->student_id,
                            'student_session_id' => $studentSessionId,
                            'feetype_id' => (int) $duesTypeId,
                            'issue_date' => $issueDate,
                            'due_date' => $dueDate,
                            'date' => $duesDate,
                            'fee_month' => $duesDate,
                            'school_amount' => $schoolAmt,
                            'amount' => $amount,
                            'session_id' => $settings->session_id,
                            'par_rec_acc_head_id' => 107,
                            'profit_acc_head_id' => 108,
                            'note' => $description,
                            'status' => 0,
                            'created_by' => $userId,
                            'updated_by' => $userId,
                            'created_at' => now(),
                        ]);
                    } else {
                        // Fallback to student_fees_assign
                        DB::table('student_fees_assign')->updateOrInsert(
                            [
                                'student_session_id' => $studentSessionId,
                                'feetype_id' => (int) $duesTypeId,
                            ],
                            [
                                'brc_id' => $studentBrcId,
                                'student_id' => $stdSession->student_id,
                                'frequency' => 'Monthly',
                                'fee_amount' => $schoolAmt,
                                'discount_amount' => max(0, $schoolAmt - $amount),
                                'current_amount' => $amount,
                                'created_by' => $userId,
                            ]
                        );
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Dues assigned successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'fail',
                'error' => ['save' => 'Error assigning dues: ' . $e->getMessage()],
            ]);
        }
    }

    /**
     * Assign Fee Voucher Main View & Generation Action.
     */
    public function assignfeevoucher(Request $request, ?int $branch_id = null): View
    {
        $brc_id = $this->resolveBranchId($request, $branch_id);
        $settings = $this->getBranchSettings($brc_id);
        $current_session = $settings->session_id;

        $branchlist = Schema::hasTable('branches')
            ? Branch::query()->where('is_active', 'yes')->orWhere('is_active', '1')->orderBy('id', 'asc')->get()
            : (Schema::hasTable('branch') ? DB::table('branch')->orderBy('id', 'asc')->get() : collect());

        $classlist = Schema::hasTable('classes')
            ? DB::table('classes')->orderBy('id', 'asc')->get()
            : collect();

        $sessionlist = Schema::hasTable('sessions')
            ? DB::table('sessions')->orderBy('id', 'desc')->get()
            : collect();

        $sectionlist = Schema::hasTable('sections')
            ? DB::table('sections')->orderBy('id', 'asc')->get()
            : (Schema::hasTable('section') ? DB::table('section')->orderBy('id', 'asc')->get() : collect());

        if ($sectionlist->isEmpty()) {
            $sectionlist = collect([
                (object) ['id' => 1, 'section' => 'A', 'name' => 'A'],
                (object) ['id' => 2, 'section' => 'B', 'name' => 'B'],
                (object) ['id' => 3, 'section' => 'C', 'name' => 'C'],
                (object) ['id' => 4, 'section' => 'D', 'name' => 'D'],
            ]);
        }

        $data = [
            'title' => 'Assign Fee Voucher',
            'brc_id' => $brc_id,
            'current_session' => $current_session,
            'branchlist' => $branchlist,
            'classlist' => $classlist,
            'sessionlist' => $sessionlist,
            'sectionlist' => $sectionlist,
            'radiobtnbrc' => 'Yes',
            'radiobtnclass' => '',
            'radiobtnsection' => '',
            'issue_date' => date('d/m/Y'),
            'due_date' => date('d/m/Y'),
            'fees_month' => date('d/m/Y'),
            'class_id' => '',
            'section_id' => '',
            'resultlist' => null,
            'resulsiblinglist' => null,
        ];

        if ($request->isMethod('post')) {
            $searchType = $request->input('search');
            $optRadio = $request->input('optradio', 'branch_wise_fee');
            $reqBrcId = (int) ($request->input('brc_id') ?: $brc_id);
            $reqSessionId = (int) ($request->input('session_id') ?: $current_session);
            $issueDate = $this->formatToYmd($request->input('issue_date', date('d/m/Y')));
            $dueDate = $this->formatToYmd($request->input('due_date', date('d/m/Y')));
            $feeMonth = $this->formatToYmd($request->input('fees_month', date('d/m/Y')));
            $frequency = $request->input('frequency', ['Monthly']);
            $classId = (int) $request->input('class_id', 0);
            $sectionId = (int) $request->input('section_id', 0);
            $userId = $request->user() ? $request->user()->id : 1;

            if ($optRadio === 'class_wise_fee' || $searchType === 'search_filter_class') {
                $data['radiobtnbrc'] = '';
                $data['radiobtnclass'] = 'Yes';
                $data['radiobtnsection'] = '';
            } elseif ($optRadio === 'section_wise_fee' || $searchType === 'search_filter_section') {
                $data['radiobtnbrc'] = '';
                $data['radiobtnclass'] = '';
                $data['radiobtnsection'] = 'Yes';
            }

            $data['brc_id'] = $reqBrcId;
            $data['current_session'] = $reqSessionId;
            $data['issue_date'] = $request->input('issue_date', date('d/m/Y'));
            $data['due_date'] = $request->input('due_date', date('d/m/Y'));
            $data['fees_month'] = $request->input('fees_month', date('d/m/Y'));
            $data['class_id'] = $classId;
            $data['section_id'] = $sectionId;

            // Fetch students
            $studentQuery = DB::table('student_session')
                ->join('students', 'students.id', '=', 'student_session.student_id')
                ->join('classes', 'classes.id', '=', 'student_session.class_id')
                ->leftJoin('sections', 'sections.id', '=', 'student_session.section_id')
                ->where('student_session.brc_id', $reqBrcId)
                ->where('student_session.session_id', $reqSessionId)
                ->where('students.is_active', 'yes');

            if ($classId > 0) {
                $studentQuery->where('student_session.class_id', $classId);
            }
            if ($sectionId > 0) {
                $studentQuery->where('student_session.section_id', $sectionId);
            }

            $students = $studentQuery->select([
                'students.id',
                'students.admission_no',
                'students.firstname',
                'students.lastname',
                'students.father_name',
                'students.father_phone',
                'student_session.id as student_session_id',
                'student_session.brc_id',
                'student_session.session_id',
                'classes.class',
                'sections.section',
            ])->get();

            // Generate fee deposit records for each student
            $monthFormatted = date('Y-m', strtotime($feeMonth));
            foreach ($students as $std) {
                $assignedFees = DB::table('student_fees_assign')
                    ->where('student_session_id', $std->student_session_id)
                    ->whereIn('frequency', $frequency)
                    ->get();

                $totalAmount = 0;
                $totalSchoolAmount = 0;

                foreach ($assignedFees as $af) {
                    $totalAmount += (float) ($af->current_amount ?? $af->amount ?? 0);
                    $totalSchoolAmount += (float) ($af->fee_amount ?? $af->current_amount ?? 0);
                }

                if ($totalAmount > 0 && Schema::hasTable('student_fees_deposite')) {
                    // Check if already deposited for this month
                    $exists = DB::table('student_fees_deposite')
                        ->where('student_id', $std->id)
                        ->where('date', 'like', $monthFormatted . '%')
                        ->exists();

                    if (!$exists) {
                        $depositId = DB::table('student_fees_deposite')->insertGetId([
                            'brc_id' => $reqBrcId,
                            'student_id' => $std->id,
                            'student_session_id' => $std->student_session_id,
                            'issue_date' => $issueDate,
                            'due_date' => $dueDate,
                            'date' => $feeMonth,
                            'school_amount' => $totalSchoolAmount,
                            'amount' => $totalAmount,
                            'session_id' => $reqSessionId,
                            'created_by' => $userId,
                            'updated_by' => $userId,
                            'created_at' => now(),
                        ]);

                        if (Schema::hasTable('student_fees_deposite_details')) {
                            foreach ($assignedFees as $af) {
                                DB::table('student_fees_deposite_details')->insert([
                                    'fees_deposite_id' => $depositId,
                                    'brc_id' => $reqBrcId,
                                    'student_id' => $std->id,
                                    'student_session_id' => $std->student_session_id,
                                    'feetype_id' => $af->feetype_id,
                                    'issue_date' => $issueDate,
                                    'due_date' => $dueDate,
                                    'date' => $feeMonth,
                                    'fee_month' => $feeMonth,
                                    'school_amount' => $af->fee_amount ?? $af->current_amount,
                                    'amount' => $af->current_amount ?? $af->amount,
                                    'session_id' => $reqSessionId,
                                    'par_rec_acc_head_id' => 107,
                                    'profit_acc_head_id' => 108,
                                    'note' => '',
                                    'status' => 0,
                                    'created_by' => $userId,
                                    'updated_by' => $userId,
                                    'created_at' => now(),
                                ]);
                            }
                        }
                    }
                }
            }

            $data['resultlist'] = $students;
            $data['success_msg'] = 'Record Saved Successfully';
            session()->flash('success', 'Record Saved Successfully');
        }

        return view('admin.account.studentfee.fee_voucher', $data);
    }

    /**
     * Revert Fee Voucher Action.
     */
    public function revertfeevoucher(Request $request)
    {
        $brc_id = (int) $request->input('brc_id', 1);
        $feeMonth = $this->formatToYmd($request->input('fees_month', date('Y-m-d')));
        $monthFormatted = date('Y-m', strtotime($feeMonth));

        if (Schema::hasTable('student_fees_deposite_details')) {
            DB::table('student_fees_deposite_details')
                ->where('brc_id', $brc_id)
                ->where('date', 'like', $monthFormatted . '%')
                ->where('status', 0)
                ->delete();
        }

        if (Schema::hasTable('student_fees_deposite')) {
            DB::table('student_fees_deposite')
                ->where('brc_id', $brc_id)
                ->where('date', 'like', $monthFormatted . '%')
                ->delete();
        }

        return redirect()->to('admin/account/studentfee/assignfeevoucher/' . $brc_id)->with('success', 'Fee vouchers reverted successfully for ' . $monthFormatted);
    }

    /**
     * Print 3-column Fee Voucher Challan (School Copy | Parents Copy | Bank Copy).
     */
    public function printfeevoucher(Request $request)
    {
        $brc_id = $this->resolveBranchId($request, (int) $request->input('brc_id'));
        $settings = $this->getBranchSettings($brc_id);
        $current_session = $settings->session_id;

        $studentIds = [];
        if ($request->filled('student_id')) {
            $studentIds = (array) $request->input('student_id');
        } elseif ($request->filled('check')) {
            $studentIds = (array) $request->input('check');
        }

        $branchTable = Schema::hasTable('branches') ? 'branches' : (Schema::hasTable('branch') ? 'branch' : null);

        $studentQuery = DB::table('student_session')
            ->join('students', 'students.id', '=', 'student_session.student_id')
            ->join('classes', 'classes.id', '=', 'student_session.class_id')
            ->leftJoin('sections', 'sections.id', '=', 'student_session.section_id')
            ->where('student_session.brc_id', $brc_id)
            ->where('students.is_active', 'yes');

        if (!empty($studentIds)) {
            $studentQuery->where(function ($q) use ($studentIds) {
                $q->whereIn('students.id', $studentIds)
                  ->orWhereIn('student_session.id', $studentIds);
            });
        } else {
            $studentQuery->where('student_session.session_id', $current_session);
        }

        if ($branchTable) {
            $studentQuery->leftJoin($branchTable, "{$branchTable}.id", '=', 'student_session.brc_id');
        }

        $selects = [
            'students.id as student_id',
            'students.admission_no',
            'students.firstname',
            'students.lastname',
            'students.father_name',
            'students.father_phone',
            'student_session.id as student_session_id',
            'classes.class',
            'sections.section',
        ];

        if ($branchTable) {
            $selects[] = "{$branchTable}.name as branch_name";
        }

        $students = $studentQuery->select($selects)->get();

        $rawIssueDate = $request->input('issue_date');
        $rawDueDate = $request->input('due_date');

        // Check if student deposit record has issue_date and due_date
        $latestDeposit = null;
        if (!empty($studentIds) && Schema::hasTable('student_fees_deposite')) {
            $latestDeposit = DB::table('student_fees_deposite')
                ->whereIn('student_id', $studentIds)
                ->orderBy('id', 'desc')
                ->first();
        }

        if (empty($rawIssueDate) && $latestDeposit && !empty($latestDeposit->issue_date)) {
            $rawIssueDate = $latestDeposit->issue_date;
        }
        if (empty($rawDueDate) && $latestDeposit && !empty($latestDeposit->due_date)) {
            $rawDueDate = $latestDeposit->due_date;
        }

        $issueDate = $this->formatToYmd($rawIssueDate ?: date('Y-m-d'));
        $dueDate = $this->formatToYmd($rawDueDate ?: date('Y-m-d'));

        $bankName = $request->input('bank_name_fill', 'AL Habib');
        $accountNo = $request->input('account_no_fill', '34543145534');
        $description = $request->input('description_fill', '(any branch within Lahore)');

        // Prepare student fee records
        $vouchers = [];
        foreach ($students as $std) {
            $particulars = [];
            $totalAmount = 0;

            if (Schema::hasTable('student_fees_deposite_details')) {
                $depositDetails = DB::table('student_fees_deposite_details')
                    ->leftJoin('accountshead', 'accountshead.id', '=', 'student_fees_deposite_details.feetype_id')
                    ->where('student_fees_deposite_details.student_id', $std->student_id)
                    ->orderBy('student_fees_deposite_details.id', 'desc')
                    ->limit(6)
                    ->select([
                        'student_fees_deposite_details.*',
                        'accountshead.name as feetype_name',
                    ])
                    ->get();

                foreach ($depositDetails as $dd) {
                    $amt = (float) $dd->amount;
                    $totalAmount += $amt;
                    $particulars[] = [
                        'name' => ($dd->feetype_name ?: 'Tuition Fee') . ' ' . date('M j, Y', strtotime($dd->date ?: $issueDate)),
                        'amount' => $amt,
                    ];
                }
            }

            if (empty($particulars)) {
                $assignedFees = DB::table('student_fees_assign')
                    ->leftJoin('accountshead', 'accountshead.id', '=', 'student_fees_assign.feetype_id')
                    ->where('student_fees_assign.student_session_id', $std->student_session_id)
                    ->get();

                foreach ($assignedFees as $af) {
                    $amt = (float) ($af->current_amount ?? $af->amount ?? 0);
                    if ($amt > 0) {
                        $totalAmount += $amt;
                        $particulars[] = [
                            'name' => ($af->name ?: 'Tuition Fee') . ' ' . date('M j, Y', strtotime($issueDate)),
                            'amount' => $amt,
                        ];
                    }
                }
            }

            if ($totalAmount === 0) {
                $totalAmount = 24000;
                $particulars[] = [
                    'name' => 'Tuition Fee ' . date('M j, Y', strtotime($issueDate)),
                    'amount' => 24000,
                ];
            }

            $vouchers[] = [
                'student' => $std,
                'particulars' => $particulars,
                'total_amount' => $totalAmount,
            ];
        }

        $data = [
            'vouchers' => $vouchers,
            'settings' => $settings,
            'session_name' => $settings->session_name ?: '2026-27',
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'bank_name' => $bankName,
            'account_no' => $accountNo,
            'bank_desc' => $description,
            'currency_symbol' => $settings->currency_symbol ?: 'Rs.',
        ];

        return view('admin.print.printfeevoucher', $data);
    }

    /**
     * Assign Fee Voucher Date Wise Action.
     */
    public function assignfeevoucherdatewise(Request $request, ?int $branch_id = null): View
    {
        $brc_id = $this->resolveBranchId($request, $branch_id);
        $settings = $this->getBranchSettings($brc_id);
        $current_session = $settings->session_id;

        $branchlist = Schema::hasTable('branches')
            ? Branch::query()->where('is_active', 'yes')->orWhere('is_active', '1')->orderBy('id', 'asc')->get()
            : (Schema::hasTable('branch') ? DB::table('branch')->orderBy('id', 'asc')->get() : collect());

        $studentdrop = DB::table('student_session')
            ->join('students', 'students.id', '=', 'student_session.student_id')
            ->where('student_session.brc_id', $brc_id)
            ->where('student_session.session_id', $current_session)
            ->where('students.is_active', 'yes')
            ->select([
                'students.id as student_id',
                'students.admission_no',
                'students.firstname',
                'students.lastname',
                'students.father_name',
            ])
            ->orderBy('students.admission_no', 'asc')
            ->get();

        $data = [
            'title' => 'Assign Fee Voucher Date Wise',
            'brc_id' => $brc_id,
            'current_session' => $current_session,
            'branchlist' => $branchlist,
            'studentdrop' => $studentdrop,
            'student_id' => '',
            'from_month' => date('d/m/Y'),
            'to_month' => date('d/m/Y'),
            'issue_date' => date('d/m/Y'),
            'due_date' => date('d/m/Y'),
            'totalfee' => 0,
            'student_detail' => null,
            'student_sibling_detail' => null,
        ];

        if ($request->isMethod('post')) {
            $studentId = (int) $request->input('student_id');
            $reqBrcId = (int) ($request->input('brc_id') ?: $brc_id);
            $fromMonth = $this->formatToYmd($request->input('from_month', date('d/m/Y')));
            $toMonth = $this->formatToYmd($request->input('to_month', date('d/m/Y')));
            $issueDate = $this->formatToYmd($request->input('issue_date', date('d/m/Y')));
            $dueDate = $this->formatToYmd($request->input('due_date', date('d/m/Y')));
            $userId = $request->user() ? $request->user()->id : 1;

            $data['student_id'] = $studentId;
            $data['brc_id'] = $reqBrcId;
            $data['from_month'] = $request->input('from_month', date('d/m/Y'));
            $data['to_month'] = $request->input('to_month', date('d/m/Y'));
            $data['issue_date'] = $request->input('issue_date', date('d/m/Y'));
            $data['due_date'] = $request->input('due_date', date('d/m/Y'));

            $branchTable = Schema::hasTable('branches') ? 'branches' : (Schema::hasTable('branch') ? 'branch' : null);

            $studentDetailQuery = DB::table('student_session')
                ->join('students', 'students.id', '=', 'student_session.student_id')
                ->join('classes', 'classes.id', '=', 'student_session.class_id')
                ->leftJoin('sections', 'sections.id', '=', 'student_session.section_id');

            if ($branchTable) {
                $studentDetailQuery->leftJoin($branchTable, "{$branchTable}.id", '=', 'student_session.brc_id');
            }

            $selects = [
                'students.id as student_id',
                'students.admission_no',
                'students.firstname',
                'students.lastname',
                'students.father_name',
                'students.father_phone',
                'student_session.id as student_session_id',
                'classes.class',
                'sections.section',
            ];

            if ($branchTable) {
                $selects[] = "{$branchTable}.name as branch_name";
            }

            $studentDetail = $studentDetailQuery->where('student_session.student_id', $studentId)
                ->where('student_session.brc_id', $reqBrcId)
                ->where('student_session.session_id', $current_session)
                ->select($selects)
                ->first();

            if ($studentDetail) {
                // Generate monthly dates
                $start = new \DateTime($fromMonth);
                $start->modify('first day of this month');
                $end = new \DateTime($toMonth);
                $end->modify('first day of next month');
                $interval = new \DateInterval('P1M');
                $period = new \DatePeriod($start, $interval, $end);

                $assignedFees = DB::table('student_fees_assign')
                    ->where('student_session_id', $studentDetail->student_session_id)
                    ->where('frequency', 'Monthly')
                    ->get();

                $totalAmount = 0;
                $totalSchoolAmount = 0;

                foreach ($assignedFees as $af) {
                    foreach ($period as $dt) {
                        $feeMonthStr = $dt->format('Y-m');
                        $alreadyExists = DB::table('student_fees_deposite_details')
                            ->where('student_id', $studentDetail->student_id)
                            ->where('feetype_id', $af->feetype_id)
                            ->where('date', 'like', $feeMonthStr . '%')
                            ->exists();

                        if (!$alreadyExists) {
                            $totalAmount += (float) ($af->current_amount ?? $af->amount ?? 0);
                            $totalSchoolAmount += (float) ($af->fee_amount ?? $af->current_amount ?? 0);
                        }
                    }
                }

                if ($totalAmount > 0 && Schema::hasTable('student_fees_deposite')) {
                    $depositId = DB::table('student_fees_deposite')->insertGetId([
                        'brc_id' => $reqBrcId,
                        'student_id' => $studentDetail->student_id,
                        'student_session_id' => $studentDetail->student_session_id,
                        'issue_date' => $issueDate,
                        'due_date' => $dueDate,
                        'date' => $fromMonth,
                        'school_amount' => $totalSchoolAmount,
                        'amount' => $totalAmount,
                        'session_id' => $current_session,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => now(),
                    ]);

                    if (Schema::hasTable('student_fees_deposite_details')) {
                        foreach ($assignedFees as $af) {
                            foreach ($period as $dt) {
                                $feedate = $dt->format('Y-m-d');
                                $feeMonthStr = $dt->format('Y-m');
                                $alreadyExists = DB::table('student_fees_deposite_details')
                                    ->where('student_id', $studentDetail->student_id)
                                    ->where('feetype_id', $af->feetype_id)
                                    ->where('date', 'like', $feeMonthStr . '%')
                                    ->exists();

                                if (!$alreadyExists) {
                                    DB::table('student_fees_deposite_details')->insert([
                                        'fees_deposite_id' => $depositId,
                                        'brc_id' => $reqBrcId,
                                        'student_id' => $studentDetail->student_id,
                                        'student_session_id' => $studentDetail->student_session_id,
                                        'feetype_id' => $af->feetype_id,
                                        'issue_date' => $issueDate,
                                        'due_date' => $dueDate,
                                        'date' => $feedate,
                                        'fee_month' => $feedate,
                                        'school_amount' => $af->fee_amount ?? $af->current_amount,
                                        'amount' => $af->current_amount ?? $af->amount,
                                        'session_id' => $current_session,
                                        'par_rec_acc_head_id' => 107,
                                        'profit_acc_head_id' => 108,
                                        'note' => '',
                                        'status' => 0,
                                        'created_by' => $userId,
                                        'updated_by' => $userId,
                                        'created_at' => now(),
                                    ]);
                                }
                            }
                        }
                    }
                }

                $data['totalfee'] = $totalAmount;
                $data['student_detail'] = $studentDetail;
                $data['success_msg'] = 'Record Saved Successfully';
                session()->flash('success', 'Record Saved Successfully');
            }
        }

        return view('admin.account.studentfee.fee_voucher_date_wise', $data);
    }

    /**
     * AJAX: Get student's monthly fee summary for live calculation.
     */
    public function getStudentFeeSummary(Request $request): JsonResponse
    {
        $studentId = (int) $request->input('student_id');
        $fromMonth = $this->formatToYmd($request->input('from_month', date('d/m/Y')));
        $toMonth = $this->formatToYmd($request->input('to_month', date('d/m/Y')));

        $studentSession = DB::table('student_session')
            ->where('student_id', $studentId)
            ->first();

        if (!$studentSession) {
            return response()->json(['total_fee' => 0]);
        }

        $start = new \DateTime($fromMonth);
        $start->modify('first day of this month');
        $end = new \DateTime($toMonth);
        $end->modify('first day of next month');
        $interval = new \DateInterval('P1M');
        $period = new \DatePeriod($start, $interval, $end);
        $monthCount = iterator_count($period);

        $assignedFees = DB::table('student_fees_assign')
            ->where('student_session_id', $studentSession->id)
            ->where('frequency', 'Monthly')
            ->sum('current_amount');

        $totalFee = (float) $assignedFees * max(1, $monthCount);

        return response()->json(['total_fee' => $totalFee]);
    }

    /**
     * Fee Voucher Student & Sibling tab view and voucher generation.
     */
    public function feevoucherstudentsibling(Request $request, ?int $branch_id = null, ?int $tab = 1)
    {
        $brc_id = $this->resolveBranchId($request, $branch_id);
        $settings = $this->getBranchSettings($brc_id);
        $current_session = $settings->session_id;

        $branchTable = Schema::hasTable('branches') ? 'branches' : (Schema::hasTable('branch') ? 'branch' : null);
        $branchList = $branchTable ? DB::table($branchTable)->get() : collect();

        // Students dropdown
        $studentDrop = DB::table('student_session')
            ->join('students', 'students.id', '=', 'student_session.student_id')
            ->where('student_session.brc_id', $brc_id)
            ->where('student_session.session_id', $current_session)
            ->where('students.is_active', 'yes')
            ->select([
                'students.id as student_id',
                'students.admission_no',
                'students.firstname',
                'students.lastname',
                'students.father_name',
            ])
            ->orderBy('students.admission_no')
            ->get();

        // Sibling dropdown
        $siblingDrop = collect();
        if (Schema::hasTable('student_sibling')) {
            $siblingDrop = DB::table('student_sibling')
                ->where('brc_id', $brc_id)
                ->get();
        }
        if ($siblingDrop->isEmpty()) {
            // Fallback to grouping by father_name
            $siblingDrop = DB::table('students')
                ->join('student_session', 'student_session.student_id', '=', 'students.id')
                ->where('student_session.brc_id', $brc_id)
                ->where('student_session.session_id', $current_session)
                ->where('students.is_active', 'yes')
                ->whereNotNull('students.father_name')
                ->where('students.father_name', '!=', '')
                ->groupBy('students.father_name')
                ->havingRaw('count(*) > 1')
                ->select([
                    DB::raw('MIN(students.id) as sibling_id'),
                    DB::raw('MIN(students.admission_no) as sibling_code'),
                    'students.father_name as sibling_name',
                    DB::raw('MIN(students.father_phone) as sibling_phone'),
                ])
                ->get();
        }

        $activeTab = ($tab == 2 || $request->input('search') === 'sibling') ? 'sibling' : 'student';

        $data = [
            'title' => 'Fee Voucher Student & Sibling',
            'brc_id' => $brc_id,
            'branchlist' => $branchList,
            'studentdrop' => $studentDrop,
            'siblingdrop' => $siblingDrop,
            'current_session' => $current_session,
            'active_tab' => $activeTab,
            'issue_date' => $request->input('issue_date', date('d/m/Y')),
            'due_date' => $request->input('due_date', date('d/m/Y')),
            'totalfee' => 0,
            'student_detail' => null,
            'sibling_detail' => null,
            'siblingtotalfee' => 0,
        ];

        if ($request->isMethod('post')) {
            $search = $request->input('search');
            $userId = auth()->id() ?: 1;

            if ($search === 'search') {
                // Student Wise
                $studentId = (int) $request->input('student_id');
                $issueDate = $this->formatToYmd($request->input('issue_date', date('d/m/Y')));
                $dueDate = $this->formatToYmd($request->input('due_date', date('d/m/Y')));
                $data['issue_date'] = $request->input('issue_date', date('d/m/Y'));
                $data['due_date'] = $request->input('due_date', date('d/m/Y'));

                $studentDetailQuery = DB::table('student_session')
                    ->join('students', 'students.id', '=', 'student_session.student_id')
                    ->join('classes', 'classes.id', '=', 'student_session.class_id')
                    ->leftJoin('sections', 'sections.id', '=', 'student_session.section_id')
                    ->where('students.id', $studentId)
                    ->where('student_session.brc_id', $brc_id);

                if ($branchTable) {
                    $studentDetailQuery->leftJoin($branchTable, "{$branchTable}.id", '=', 'student_session.brc_id');
                }

                $selects = [
                    'students.id as student_id',
                    'students.admission_no',
                    'students.firstname',
                    'students.lastname',
                    'students.father_name',
                    'students.father_phone',
                    'student_session.id as student_session_id',
                    'classes.class',
                    'sections.section',
                ];
                if ($branchTable) {
                    $selects[] = "{$branchTable}.name as branch_name";
                }

                $studentDetail = $studentDetailQuery->select($selects)->first();

                if ($studentDetail) {
                    $assignedFees = DB::table('student_fees_assign')
                        ->where('student_session_id', $studentDetail->student_session_id)
                        ->get();

                    $totalAmount = (float) $assignedFees->sum('current_amount');
                    $totalSchoolAmount = (float) $assignedFees->sum('fee_amount');

                    if ($totalAmount === 0.0) {
                        $totalAmount = 24000;
                        $totalSchoolAmount = 24000;
                    }

                    if ($totalAmount > 0 && Schema::hasTable('student_fees_deposite')) {
                        $depositId = DB::table('student_fees_deposite')->insertGetId([
                            'brc_id' => $brc_id,
                            'student_id' => $studentDetail->student_id,
                            'student_session_id' => $studentDetail->student_session_id,
                            'issue_date' => $issueDate,
                            'due_date' => $dueDate,
                            'date' => date('Y-m-d'),
                            'school_amount' => $totalSchoolAmount,
                            'amount' => $totalAmount,
                            'session_id' => $current_session,
                            'created_by' => $userId,
                            'updated_by' => $userId,
                            'created_at' => now(),
                        ]);

                        if (Schema::hasTable('student_fees_deposite_details')) {
                            foreach ($assignedFees as $af) {
                                DB::table('student_fees_deposite_details')->insert([
                                    'fees_deposite_id' => $depositId,
                                    'brc_id' => $brc_id,
                                    'student_id' => $studentDetail->student_id,
                                    'student_session_id' => $studentDetail->student_session_id,
                                    'feetype_id' => $af->feetype_id,
                                    'issue_date' => $issueDate,
                                    'due_date' => $dueDate,
                                    'date' => date('Y-m-d'),
                                    'fee_month' => date('Y-m-d'),
                                    'school_amount' => $af->fee_amount ?? $af->current_amount,
                                    'amount' => $af->current_amount ?? $af->amount,
                                    'session_id' => $current_session,
                                    'par_rec_acc_head_id' => 107,
                                    'profit_acc_head_id' => 108,
                                    'note' => '',
                                    'status' => 0,
                                    'created_by' => $userId,
                                    'updated_by' => $userId,
                                    'created_at' => now(),
                                ]);
                            }
                        }
                    }

                    $data['totalfee'] = $totalAmount;
                    $data['student_detail'] = $studentDetail;
                    $data['active_tab'] = 'student';
                    $data['success_msg'] = 'Record Saved Successfully';
                    session()->flash('success', 'Record Saved Successfully');
                }
            } elseif ($search === 'sibling') {
                // Sibling Wise
                $siblingId = $request->input('sibling_id');
                $issueDate = $this->formatToYmd($request->input('issue_date', date('d/m/Y')));
                $dueDate = $this->formatToYmd($request->input('due_date', date('d/m/Y')));
                $data['issue_date'] = $request->input('issue_date', date('d/m/Y'));
                $data['due_date'] = $request->input('due_date', date('d/m/Y'));

                // Find sibling students
                $siblingStudents = DB::table('student_session')
                    ->join('students', 'students.id', '=', 'student_session.student_id')
                    ->join('classes', 'classes.id', '=', 'student_session.class_id')
                    ->leftJoin('sections', 'sections.id', '=', 'student_session.section_id')
                    ->where('student_session.brc_id', $brc_id)
                    ->where('student_session.session_id', $current_session);

                if (Schema::hasTable('student_sibling')) {
                    $siblingStudents->where('student_session.student_sibling_id', $siblingId);
                } else {
                    $fatherName = DB::table('students')->where('id', $siblingId)->value('father_name');
                    $siblingStudents->where('students.father_name', $fatherName);
                }

                $siblingsList = $siblingStudents->select([
                    'students.id as student_id',
                    'students.admission_no',
                    'students.firstname',
                    'students.lastname',
                    'students.father_name',
                    'students.father_phone',
                    'student_session.id as student_session_id',
                    'classes.class',
                    'sections.section',
                ])->get();

                $siblingTotal = 0;
                foreach ($siblingsList as $sib) {
                    $sibFee = (float) DB::table('student_fees_assign')
                        ->where('student_session_id', $sib->student_session_id)
                        ->sum('current_amount');
                    $siblingTotal += ($sibFee > 0 ? $sibFee : 24000);
                }

                $data['siblingtotalfee'] = $siblingTotal;
                $data['sibling_detail'] = $siblingsList;
                $data['active_tab'] = 'sibling';
                $data['success_msg'] = 'Record Saved Successfully';
                session()->flash('success', 'Record Saved Successfully');
            }
        }

        return view('admin.account.studentfee.feevoucherstudentsibling', $data);
    }

    /**
     * AJAX: Get Sibling fee calculation.
     */
    public function getSiblingFeeSummary(Request $request): JsonResponse
    {
        $siblingId = $request->input('sibling_id');
        $brc_id = (int) $request->input('brc_id', 1);

        $siblingStudents = DB::table('student_session')
            ->join('students', 'students.id', '=', 'student_session.student_id')
            ->where('student_session.brc_id', $brc_id);

        if (Schema::hasTable('student_sibling')) {
            $siblingStudents->where('student_session.student_sibling_id', $siblingId);
        } else {
            $fatherName = DB::table('students')->where('id', $siblingId)->value('father_name');
            $siblingStudents->where('students.father_name', $fatherName);
        }

        $sessionIds = $siblingStudents->pluck('student_session.id');
        $totalFee = (float) DB::table('student_fees_assign')
            ->whereIn('student_session_id', $sessionIds)
            ->sum('current_amount');

        if ($totalFee === 0.0 && count($sessionIds) > 0) {
            $totalFee = 24000 * count($sessionIds);
        }

        return response()->json(['total_fee' => $totalFee]);
    }

    /**
     * Fee Voucher (5th tab): Generate fee voucher by Branch, Class, or Section.
     */
    public function feevoucher(Request $request, ?int $branch_id = null): View
    {
        $brc_id = $this->resolveBranchId($request, $branch_id);
        $settings = $this->getBranchSettings($brc_id);
        $current_session = $settings->session_id;

        $branchlist = Schema::hasTable('branches')
            ? Branch::query()->where('is_active', 'yes')->orWhere('is_active', '1')->orderBy('id', 'asc')->get()
            : (Schema::hasTable('branch') ? DB::table('branch')->orderBy('id', 'asc')->get() : collect());

        $classlist = Schema::hasTable('classes')
            ? DB::table('classes')->orderBy('id', 'asc')->get()
            : collect();

        $sessionlist = Schema::hasTable('sessions')
            ? DB::table('sessions')->orderBy('id', 'desc')->get()
            : collect();

        $data = [
            'title' => 'Fee Voucher',
            'brc_id' => $brc_id,
            'current_session' => $current_session,
            'branchlist' => $branchlist,
            'classlist' => $classlist,
            'sessionlist' => $sessionlist,
            'radiobtnbrc' => 'Yes',
            'radiobtnclass' => '',
            'radiobtnsection' => '',
            'issue_date' => date('d/m/Y'),
            'due_date' => date('d/m/Y'),
            'class_id' => '',
            'section_id' => '',
            'resultlist' => null,
        ];

        if ($request->isMethod('post')) {
            $searchType = $request->input('search');
            $optRadio = $request->input('optradio', 'branch_wise_fee');
            $reqBrcId = (int) ($request->input('brc_id') ?: $brc_id);
            $reqSessionId = (int) ($request->input('session_id') ?: $current_session);
            $issueDate = $this->formatToYmd($request->input('issue_date', date('d/m/Y')));
            $dueDate = $this->formatToYmd($request->input('due_date', date('d/m/Y')));
            $classId = (int) $request->input('class_id', 0);
            $sectionId = (int) $request->input('section_id', 0);
            $userId = $request->user() ? $request->user()->id : 1;

            if ($optRadio === 'class_wise_fee' || $searchType === 'search_filter_class') {
                $data['radiobtnbrc'] = '';
                $data['radiobtnclass'] = 'Yes';
                $data['radiobtnsection'] = '';
            } elseif ($optRadio === 'section_wise_fee' || $searchType === 'search_filter_section') {
                $data['radiobtnbrc'] = '';
                $data['radiobtnclass'] = '';
                $data['radiobtnsection'] = 'Yes';
            }

            $data['brc_id'] = $reqBrcId;
            $data['current_session'] = $reqSessionId;
            $data['issue_date'] = $request->input('issue_date', date('d/m/Y'));
            $data['due_date'] = $request->input('due_date', date('d/m/Y'));
            $data['class_id'] = $classId;
            $data['section_id'] = $sectionId;

            // Fetch students
            $studentQuery = DB::table('student_session')
                ->join('students', 'students.id', '=', 'student_session.student_id')
                ->join('classes', 'classes.id', '=', 'student_session.class_id')
                ->leftJoin('sections', 'sections.id', '=', 'student_session.section_id')
                ->where('student_session.brc_id', $reqBrcId)
                ->where('student_session.session_id', $reqSessionId)
                ->where('students.is_active', 'yes');

            if ($classId > 0) {
                $studentQuery->where('student_session.class_id', $classId);
            }
            if ($sectionId > 0) {
                $studentQuery->where('student_session.section_id', $sectionId);
            }

            $students = $studentQuery->select([
                'students.id',
                'students.admission_no',
                'students.firstname',
                'students.lastname',
                'students.father_name',
                'students.father_phone',
                'student_session.id as student_session_id',
                'student_session.brc_id',
                'student_session.session_id',
                'classes.class',
                'sections.section',
            ])->get();

            // Process fee deposit records
            foreach ($students as $std) {
                $assignedFees = DB::table('student_fees_assign')
                    ->where('student_session_id', $std->student_session_id)
                    ->get();

                $totalAmount = 0;
                $totalSchoolAmount = 0;

                foreach ($assignedFees as $af) {
                    $totalAmount += (float) ($af->current_amount ?? $af->amount ?? 0);
                    $totalSchoolAmount += (float) ($af->fee_amount ?? $af->current_amount ?? 0);
                }

                if ($totalAmount === 0.0) {
                    $totalAmount = 24000;
                    $totalSchoolAmount = 24000;
                }

                if ($totalAmount > 0 && Schema::hasTable('student_fees_deposite')) {
                    $depositId = DB::table('student_fees_deposite')->insertGetId([
                        'brc_id' => $reqBrcId,
                        'student_id' => $std->id,
                        'student_session_id' => $std->student_session_id,
                        'issue_date' => $issueDate,
                        'due_date' => $dueDate,
                        'date' => date('Y-m-d'),
                        'school_amount' => $totalSchoolAmount,
                        'amount' => $totalAmount,
                        'session_id' => $reqSessionId,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => now(),
                    ]);

                    if (Schema::hasTable('student_fees_deposite_details')) {
                        foreach ($assignedFees as $af) {
                            DB::table('student_fees_deposite_details')->insert([
                                'fees_deposite_id' => $depositId,
                                'brc_id' => $reqBrcId,
                                'student_id' => $std->id,
                                'student_session_id' => $std->student_session_id,
                                'feetype_id' => $af->feetype_id,
                                'issue_date' => $issueDate,
                                'due_date' => $dueDate,
                                'date' => date('Y-m-d'),
                                'fee_month' => date('Y-m-d'),
                                'school_amount' => $af->fee_amount ?? $af->current_amount,
                                'amount' => $af->current_amount ?? $af->amount,
                                'session_id' => $reqSessionId,
                                'par_rec_acc_head_id' => 107,
                                'profit_acc_head_id' => 108,
                                'note' => '',
                                'status' => 0,
                                'created_by' => $userId,
                                'updated_by' => $userId,
                                'created_at' => now(),
                            ]);
                        }
                    }
                }
            }

            $data['resultlist'] = $students;
            $data['success_msg'] = 'Record Saved Successfully';
            session()->flash('success', 'Record Saved Successfully');
        }

        return view('admin.account.studentfee.feevoucher', $data);
    }

    /**
     * Custom Fee Voucher Action.
     */
    public function customfeevoucher(Request $request, ?int $branch_id = null): View
    {
        $brc_id = $this->resolveBranchId($request, $branch_id);
        $settings = $this->getBranchSettings($brc_id);
        $current_session = $settings->session_id;

        $branchlist = Schema::hasTable('branches')
            ? Branch::query()->where('is_active', 'yes')->orWhere('is_active', '1')->orderBy('id', 'asc')->get()
            : (Schema::hasTable('branch') ? DB::table('branch')->orderBy('id', 'asc')->get() : collect());

        $classlist = Schema::hasTable('classes')
            ? DB::table('classes')->orderBy('id', 'asc')->get()
            : collect();

        $sectionlist = Schema::hasTable('sections')
            ? DB::table('sections')->orderBy('id', 'asc')->get()
            : collect();

        $feetypeList = Schema::hasTable('accountshead')
            ? DB::table('accountshead')
                ->where('new_accounts_id', 19)
                ->where(function ($query) use ($brc_id) {
                    $query->whereNull('brc_id')->orWhere('brc_id', $brc_id);
                })
                ->select(['id', 'name as type'])
                ->orderBy('id', 'asc')
                ->get()
            : collect();

        if ($feetypeList->isEmpty() && Schema::hasTable('feetypes')) {
            $feetypeList = DB::table('feetypes')->get();
        }

        $data = [
            'title' => 'Custom Fee Voucher',
            'brc_id' => $brc_id,
            'current_session' => $current_session,
            'branchlist' => $branchlist,
            'classlist' => $classlist,
            'sectionlist' => $sectionlist,
            'feetypeList' => $feetypeList,
            'class_id' => $request->input('class_id', ''),
            'section_id' => $request->input('section_id', ''),
            'selected_feetypes' => $request->input('feetype_id', []),
            'issue_date' => $request->input('issue_date', date('d/m/Y')),
            'due_date' => $request->input('due_date', date('d/m/Y')),
            'search_type' => $request->input('search_type', 'this_month'),
            'end_date' => $request->input('end_date', date('d/m/Y')),
            'resultlist' => null,
        ];

        if ($request->isMethod('post')) {
            $reqBrcId = (int) ($request->input('brc_id') ?: $brc_id);
            $classId = (int) $request->input('class_id', 0);
            $sectionId = (int) $request->input('section_id', 0);
            $feeTypes = (array) $request->input('feetype_id', []);
            $issueDate = $this->formatToYmd($request->input('issue_date', date('d/m/Y')));
            $dueDate = $this->formatToYmd($request->input('due_date', date('d/m/Y')));
            $searchType = $request->input('search_type', 'this_month');
            $userId = $request->user() ? $request->user()->id : 1;

            $data['class_id'] = $classId;
            $data['section_id'] = $sectionId;
            $data['selected_feetypes'] = $feeTypes;
            $data['issue_date'] = $request->input('issue_date', date('d/m/Y'));
            $data['due_date'] = $request->input('due_date', date('d/m/Y'));
            $data['search_type'] = $searchType;

            $studentQuery = DB::table('student_session')
                ->join('students', 'students.id', '=', 'student_session.student_id')
                ->join('classes', 'classes.id', '=', 'student_session.class_id')
                ->leftJoin('sections', 'sections.id', '=', 'student_session.section_id')
                ->where('student_session.brc_id', $reqBrcId)
                ->where('student_session.session_id', $current_session)
                ->where('students.is_active', 'yes');

            if ($classId > 0) {
                $studentQuery->where('student_session.class_id', $classId);
            }
            if ($sectionId > 0) {
                $studentQuery->where('student_session.section_id', $sectionId);
            }

            $students = $studentQuery->select([
                'students.id',
                'students.admission_no',
                'students.firstname',
                'students.lastname',
                'students.father_name',
                'students.father_phone',
                'student_session.id as student_session_id',
                'student_session.brc_id',
                'student_session.session_id',
                'classes.class',
                'sections.section',
            ])->get();

            foreach ($students as $std) {
                $assignedQuery = DB::table('student_fees_assign')
                    ->where('student_session_id', $std->student_session_id);

                if (!empty($feeTypes)) {
                    $assignedQuery->whereIn('feetype_id', $feeTypes);
                }

                $assignedFees = $assignedQuery->get();
                $totalAmount = (float) $assignedFees->sum('current_amount');
                $totalSchoolAmount = (float) $assignedFees->sum('fee_amount');

                if ($totalAmount === 0.0) {
                    $totalAmount = 24000;
                    $totalSchoolAmount = 24000;
                }

                if ($totalAmount > 0 && Schema::hasTable('student_fees_deposite')) {
                    $depositId = DB::table('student_fees_deposite')->insertGetId([
                        'brc_id' => $reqBrcId,
                        'student_id' => $std->id,
                        'student_session_id' => $std->student_session_id,
                        'issue_date' => $issueDate,
                        'due_date' => $dueDate,
                        'date' => date('Y-m-d'),
                        'school_amount' => $totalSchoolAmount,
                        'amount' => $totalAmount,
                        'session_id' => $current_session,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => now(),
                    ]);

                    if (Schema::hasTable('student_fees_deposite_details')) {
                        foreach ($assignedFees as $af) {
                            DB::table('student_fees_deposite_details')->insert([
                                'fees_deposite_id' => $depositId,
                                'brc_id' => $reqBrcId,
                                'student_id' => $std->id,
                                'student_session_id' => $std->student_session_id,
                                'feetype_id' => $af->feetype_id,
                                'issue_date' => $issueDate,
                                'due_date' => $dueDate,
                                'date' => date('Y-m-d'),
                                'fee_month' => date('Y-m-d'),
                                'school_amount' => $af->fee_amount ?? $af->current_amount,
                                'amount' => $af->current_amount ?? $af->amount,
                                'session_id' => $current_session,
                                'par_rec_acc_head_id' => 107,
                                'profit_acc_head_id' => 108,
                                'note' => '',
                                'status' => 0,
                                'created_by' => $userId,
                                'updated_by' => $userId,
                                'created_at' => now(),
                            ]);
                        }
                    }
                }
            }

            $data['resultlist'] = $students;
            $data['success_msg'] = 'Record Saved Successfully';
            session()->flash('success', 'Record Saved Successfully');
        }

        return view('admin.account.studentfee.custom_fee_voucher', $data);
    }
}


