<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Models\Hrms\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PerformanceAndComplianceController extends Controller
{
    public function index(string $type, Request $request): View { return view('admin.hrms.performance-compliance.form', $this->data($type, $request)); }
    public function store(string $type, Request $request): RedirectResponse { return $this->save($type, $request); }
    public function edit(string $type, int $record, Request $request): View { return view('admin.hrms.performance-compliance.form', $this->data($type, $request, $record)); }
    public function update(string $type, int $record, Request $request): RedirectResponse { return $this->save($type, $request, $record); }
    public function show(string $type, int $record, Request $request): View { return view('admin.hrms.performance-compliance.form', $this->data($type, $request, $record, true)); }
    public function destroy(string $type, int $record): RedirectResponse { DB::table($this->config($type)['table'])->where('id', $record)->delete(); return back()->with('success', 'Record deleted successfully.'); }

    private function save(string $type, Request $request, ?int $record = null): RedirectResponse
    {
        $config = $this->config($type);
        $request->validate($config['rules']);
        $payload = $request->except(['_token', '_method', 'person_name', 'reference_no', 'form_date', 'total_score', 'grade']);
        $score = $this->score($type, $payload);
        $values = [
            'person_name' => $request->input('person_name'), 'reference_no' => $request->input('reference_no'),
            'form_date' => $request->input('form_date'), 'total_score' => $score['total'], 'grade' => $score['grade'],
            'payload' => json_encode($payload), 'updated_at' => now(),
        ];
        if ($record) DB::table($config['table'])->where('id', $record)->update($values);
        else { $values['created_at'] = now(); DB::table($config['table'])->insert($values); }
        return to_route($config['route'])->with('success', $record ? 'Record updated successfully.' : 'Record saved successfully.');
    }

    private function data(string $type, Request $request, ?int $record = null, bool $view = false): array
    {
        $config = $this->config($type); $model = $record ? DB::table($config['table'])->find($record) : null;
        if ($model) $model->payload = json_decode($model->payload ?: '{}', true);
        return ['type'=>$type, 'config'=>$config, 'record'=>$model, 'viewMode'=>$view,
            'records'=>DB::table($config['table'])->latest('id')->get(), 'staff'=>$this->staff(),
            'campuses'=>DB::table('branch')->where('is_active',1)->orderBy('name')->get(), 'today'=>now()->toDateString()];
    }

    private function staff() { return Staff::query()->where('is_active',1)->orderBy('name')->orderBy('surname')->get(['id','name','surname','employee_id']); }
    private function score(string $type, array $payload): array
    {
        $values=[]; foreach ($payload as $key=>$value) if (is_numeric($value)) $values[]=(float)$value;
        $total=(int)array_sum($values); $max=$type==='school_performance'?200:($type==='annual_teacher'||$type==='annual_management'?500:0);
        $percentage=$max ? $total/$max*100 : 0; $grade=$percentage>=80?'A':($percentage>=70?'B':($percentage>=60?'C':($percentage>=50?'D':'F')));
        return ['total'=>$total,'grade'=>$max?$grade:null];
    }

    private function config(string $type): array
    {
        $staffRules=['person_name'=>'nullable|string|max:255','form_date'=>'nullable|date'];
        $common=['table'=>'','route'=>'','title'=>'','personLabel'=>'Staff Name','fields'=>[],'rules'=>$staffRules,'columns'=>[]];
        $configs=[
            'school_performance'=>array_merge($common,['table'=>'school_performance_report','route'=>'admin.hrms.school-performance.index','title'=>'School Performance Report','personLabel'=>'Campus','fields'=>[['name'=>'campus','label'=>'Campus','type'=>'campus','required'=>true],['name'=>'scores','label'=>'Performance Scores','type'=>'school_scores'],['name'=>'auditor_sign','label'=>'Auditor Signature','type'=>'text'],['name'=>'coordinator_sign','label'=>'Coordinator Signature','type'=>'text'],['name'=>'principal_sign','label'=>'Principal Signature','type'=>'text']],'rules'=>['campus'=>'required','scores'=>'array','scores.*'=>'nullable|integer|min:1|max:5'],'columns'=>['S.No.','Campus','Report ID','Total Score','Created Date','Action']]),
            'monthly_teacher'=>array_merge($common,['table'=>'monthly_appraisal','route'=>'admin.hrms.monthly-teacher.index','title'=>'Monthly Appraisal','personLabel'=>'Teacher Name','fields'=>[['name'=>'person_name','label'=>'Teacher Name','type'=>'staff','required'=>true],['name'=>'employee_code','label'=>'Employee Code','type'=>'text'],['name'=>'form_date','label'=>'Appraisal Date','type'=>'date','required'=>true],['name'=>'monthly_scores','label'=>'Monthly Scores','type'=>'monthly_scores']],'rules'=>['person_name'=>'required','form_date'=>'required|date','monthly_scores'=>'array'] ,'columns'=>['Teacher Name','Employee Code','Appraisal Date','Total Score','Performance','Action']]),
            'monthly_management'=>array_merge($common,['table'=>'monthly_appraisal_management','route'=>'admin.hrms.monthly-management.index','title'=>'Monthly Appraisal - Management','personLabel'=>'Staff Name','fields'=>[['name'=>'person_name','label'=>'Staff Name','type'=>'staff','required'=>true],['name'=>'employee_code','label'=>'Employee Code','type'=>'text'],['name'=>'form_date','label'=>'Appraisal Date','type'=>'date','required'=>true],['name'=>'monthly_scores','label'=>'Monthly Scores','type'=>'monthly_scores']],'rules'=>['person_name'=>'required','form_date'=>'required|date','monthly_scores'=>'array'],'columns'=>['Staff Name','Employee Code','Appraisal Date','Total Score','Performance','Action']]),
            'annual_teacher'=>array_merge($common,['table'=>'annual_confidential_report','route'=>'admin.hrms.annual-teacher.index','title'=>'Annual Confidential Report','personLabel'=>'Teacher Name','fields'=>$this->annualFields('teacher'),'rules'=>['person_name'=>'required','form_date'=>'required|date'],'columns'=>['Teacher Name','Department','Designation','Report Date','Total Score','Grade','Action']]),
            'annual_management'=>array_merge($common,['table'=>'annual_confidential_report_management','route'=>'admin.hrms.annual-management.index','title'=>'Annual Confidential Report - Management','personLabel'=>'Staff Name','fields'=>$this->annualFields('management'),'rules'=>['person_name'=>'required','form_date'=>'required|date'],'columns'=>['Staff Name','Department','Designation','Report Date','Total Score','Grade','Action']]),
            'notice_reply'=>array_merge($common,['table'=>'non_conference_notice_reply','route'=>'admin.hrms.notice-reply.index','title'=>'Non-Conformance Notice Reply','fields'=>[['name'=>'reference_no','label'=>'Ref No.','type'=>'text'],['name'=>'form_date','label'=>'Date','type'=>'date','required'=>true],['name'=>'person_name','label'=>'Employee Name','type'=>'staff','required'=>true],['name'=>'designation','label'=>'Designation','type'=>'text'],['name'=>'subject','label'=>'Subject','type'=>'text'],['name'=>'explanation_text','label'=>'Explanation','type'=>'textarea','required'=>true]],'rules'=>['person_name'=>'required','form_date'=>'required|date','explanation_text'=>'required'],'columns'=>['Ref No','Employee Name','Designation','Date','Subject','Action']]),
            'clearance'=>array_merge($common,['table'=>'clearance_forms','route'=>'admin.hrms.clearance.index','title'=>'Clearance Form','fields'=>[['name'=>'person_name','label'=>'Employee','type'=>'staff','required'=>true],['name'=>'father_husband_name','label'=>'Father/Husband Name','type'=>'text'],['name'=>'employee_id','label'=>'Employee ID','type'=>'text'],['name'=>'position','label'=>'Position','type'=>'text'],['name'=>'department','label'=>'Department','type'=>'text'],['name'=>'joining_date','label'=>'Date of Joining','type'=>'date'],['name'=>'employee_status','label'=>'Employee Status','type'=>'radio','options'=>['Regular Faculty','Regular Staff','Substitute','Contractual']],['name'=>'last_day_employment','label'=>'Last Day of Employment','type'=>'date'],['name'=>'clearance_items','label'=>'Clearance Items','type'=>'clearance_items']],'rules'=>['person_name'=>'required','clearance_items'=>'array'],'columns'=>['ID','Employee Name','Employee ID','Position','Last Day','Action']]),
            'exit_interview'=>array_merge($common,['table'=>'exit_interviews','route'=>'admin.hrms.exit-interview.index','title'=>'Exit Interview','fields'=>[['name'=>'person_name','label'=>'Employee','type'=>'staff','required'=>true],['name'=>'position','label'=>'Position','type'=>'text'],['name'=>'form_date','label'=>'Date of Interview','type'=>'date','required'=>true],['name'=>'leaving_reasons','label'=>'Reasons for Leaving','type'=>'checks','options'=>['Higher pay','Better career opportunity','Career change','Conflict with other employees','Better benefits','Improved work life balance','Closer to home','Conflict with managers','Family and/or personal reasons','Company instability','Other']],['name'=>'job_ratings','label'=>'The Job Itself','type'=>'ratings'],['name'=>'job_improvements','label'=>'What could improve the job?','type'=>'textarea']],'rules'=>['person_name'=>'required','form_date'=>'required|date','leaving_reasons'=>'array','job_ratings'=>'array'],'columns'=>['ID','Employee Name','Position','Interview Date','Action']]),
            'final_settlement'=>array_merge($common,['table'=>'final_settlement_form','route'=>'admin.hrms.final-settlement.index','title'=>'Final Settlement Form','fields'=>[['name'=>'person_name','label'=>'Employee Name','type'=>'staff','required'=>true],['name'=>'designation','label'=>'Designation','type'=>'text'],['name'=>'cnic','label'=>'CNIC','type'=>'text'],['name'=>'appointed_as','label'=>'Appointed as','type'=>'text'],['name'=>'relieved_as','label'=>'Relieved as','type'=>'text'],['name'=>'joining_date','label'=>'Joining Date','type'=>'date'],['name'=>'leaving_date','label'=>'Leaving Date','type'=>'date'],['name'=>'amounts','label'=>'Amount Details','type'=>'amounts']],'rules'=>['person_name'=>'required'],'columns'=>['Employee Name','Designation','Joining Date','Leaving Date','Payable','Receivable','Action']]),
            'show_cause'=>array_merge($common,['table'=>'show_cause_notices','route'=>'admin.hrms.show-cause.index','title'=>'Show Cause Notice','fields'=>[['name'=>'reference_no','label'=>'Ref No.','type'=>'text'],['name'=>'form_date','label'=>'Date','type'=>'date','required'=>true],['name'=>'person_name','label'=>'Name of Employee','type'=>'staff','required'=>true],['name'=>'designation','label'=>'Designation','type'=>'text'],['name'=>'violations','label'=>'Violations','type'=>'repeat'],['name'=>'reply_due_date','label'=>'Reply Due Date','type'=>'date','required'=>true]],'rules'=>['person_name'=>'required','form_date'=>'required|date','reply_due_date'=>'required|date'],'columns'=>['ID','Ref No.','Date','Employee Name','Designation','Reply Due Date','Action']]),
            'inquiry'=>array_merge($common,['table'=>'inquiry_processes','route'=>'admin.hrms.inquiry.index','title'=>'Inquiry Process','fields'=>[['name'=>'reference_no','label'=>'Ref No.','type'=>'text'],['name'=>'form_date','label'=>'Date','type'=>'date','required'=>true],['name'=>'inquiry_title','label'=>'Inquiry Title','type'=>'text','required'=>true],['name'=>'inquiry_reference','label'=>'Inquiry Reference','type'=>'text'],['name'=>'person_name','label'=>'Inquiry Against','type'=>'staff','required'=>true],['name'=>'inquiry_officer','label'=>'Inquiry Officer/Committee','type'=>'staff'],['name'=>'statements','label'=>'Statement Confirmations','type'=>'repeat'],['name'=>'proceedings','label'=>'Proceedings','type'=>'textarea']],'rules'=>['form_date'=>'required|date','inquiry_title'=>'required','person_name'=>'required'],'columns'=>['ID','Ref No.','Date','Inquiry Title','Inquiry Against','Action']]),
        ];
        return $configs[$type] ?? abort(404);
    }

    private function annualFields(string $kind): array { return [['name'=>'person_name','label'=>$kind==='teacher'?'Teacher Name':'Staff Name','type'=>'staff','required'=>true],['name'=>'department','label'=>'Department','type'=>'text'],['name'=>'designation','label'=>'Designation','type'=>'text'],['name'=>'joining_date','label'=>'Date of Joining','type'=>'date'],['name'=>'reporting_to','label'=>'Reporting To','type'=>'text'],['name'=>'form_date','label'=>'Report Date','type'=>'date','required'=>true],['name'=>'annual_scores','label'=>'Parameters & Scores','type'=>'annual_scores'],['name'=>'recommendation_promotion','label'=>'Recommendation for Promotion','type'=>'textarea'],['name'=>'recommendation_increment','label'=>'Recommendation for Increment','type'=>'textarea'],['name'=>'hod_signature','label'=>'HOD Signature','type'=>'text'],['name'=>'school_head_signature','label'=>'School Head Signature','type'=>'text'],['name'=>'chairman_signature','label'=>'Chairman Signature','type'=>'text']]; }
}
