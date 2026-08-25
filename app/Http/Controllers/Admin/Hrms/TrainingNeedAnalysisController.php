<?php
namespace App\Http\Controllers\Admin\Hrms;
use App\Http\Controllers\Controller; use App\Models\Hrms\Staff; use App\Models\Hrms\TrainingNeedAnalysis; use Illuminate\Http\Request; use Illuminate\Http\RedirectResponse; use Illuminate\View\View;
class TrainingNeedAnalysisController extends Controller {
    private const MODES=['On-the-job Training','Off-the-job Training','Workshop','Seminar','Online Training'];
    public function index(Request $request): View { return view('admin.hrms.training.analysis',$this->data($request)); }
    public function store(Request $request): RedirectResponse { TrainingNeedAnalysis::create($this->validated($request)); return to_route('admin.hrms.training.analysis.index')->with('success','Training Need Analysis created successfully.'); }
    public function edit(TrainingNeedAnalysis $trainingNeedAnalysis, Request $request): View { return view('admin.hrms.training.analysis',$this->data($request,$trainingNeedAnalysis)); }
    public function update(Request $request, TrainingNeedAnalysis $trainingNeedAnalysis): RedirectResponse { $trainingNeedAnalysis->update($this->validated($request)); return to_route('admin.hrms.training.analysis.index')->with('success','Training Need Analysis updated successfully.'); }
    public function show(TrainingNeedAnalysis $trainingNeedAnalysis, Request $request): View { return view('admin.hrms.training.analysis',$this->data($request,$trainingNeedAnalysis,true)); }
    public function destroy(TrainingNeedAnalysis $trainingNeedAnalysis): RedirectResponse { $trainingNeedAnalysis->delete(); return back()->with('success','Training Need Analysis deleted successfully.'); }
    private function validated(Request $r): array { return $r->validate(['name'=>'required|string|max:255','designation'=>'required|string|max:255','relevant_pod'=>'nullable|string','campus'=>'nullable|string|max:255','tna_date'=>'required|date','major_task'=>'nullable|string','target_competencies'=>'nullable|string','mode_of_training'=>'nullable|string|max:255','required_arrangement'=>'nullable|string','school_benefits'=>'nullable|string','last_training_program'=>'nullable|string','suggest_training'=>'nullable|string','requester_sign'=>'nullable|string|max:255','hod_hrm_admin_sign'=>'nullable|string|max:255']); }
    private function data(Request $r, ?TrainingNeedAnalysis $analysis=null, bool $view=false): array { return ['analyses'=>TrainingNeedAnalysis::latest()->get(),'analysis'=>$analysis,'viewMode'=>$view,'staff'=>$this->staff($r),'modes'=>self::MODES,'today'=>now()->toDateString()]; }
    private function staff(Request $r) { return Staff::query()->where('is_active',1)->orderBy('name')->orderBy('surname')->get(['id','name','surname']); }
}
