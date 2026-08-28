<?php

namespace App\Http\Controllers\Admin\Academics;

use App\Models\Academics\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ChapterController extends BaseAcademicsController
{
    private function subjects($classId) { return DB::table('subjects')->join('subject_group_subjects','subject_group_subjects.subject_id','=','subjects.id')->join('subject_groups','subject_groups.id','=','subject_group_subjects.subject_group_id')->where('subject_groups.class_id',$classId)->select('subjects.*')->distinct()->orderBy('subjects.name')->get(); }
    public function index(Request $request): View { $classes=DB::table('classes')->orderBy('id')->get(); $chapter=$request->integer('edit')?Chapter::findOrFail($request->integer('edit')):null; $selectedClass=$request->integer('class_id')?:($chapter?->class_id); $selectedSubject=$request->integer('subject_id')?:($chapter?->subject_id); $subjects=$selectedClass?$this->subjects($selectedClass):collect(); $classNames=$classes->pluck('class','id'); $records=Chapter::with('subject')->when($request->filled('search'),fn($q)=>$q->where('name','like','%'.$request->string('search').'%')->orWhere('urdu','like','%'.$request->string('search').'%'))->orderBy('id')->paginate(20)->withQueryString(); return view('admin.academics.curriculum.chapters',compact('classes','subjects','selectedClass','selectedSubject','classNames','records','chapter')); }
    public function store(Request $request) { $d=$request->validate(['class_id'=>'required|exists:classes,id','subject_id'=>'required|exists:subjects,id','eng_name'=>'required|array','eng_name.*'=>'required|string','urdu_name'=>'nullable|array']); foreach($d['eng_name'] as $i=>$n) Chapter::create(['class_id'=>$d['class_id'],'subject_id'=>$d['subject_id'],'name'=>$n,'urdu'=>$d['urdu_name'][$i]??'','is_active'=>'yes']); return back()->with('success','Chapter added successfully.'); }
    public function import(Request $request) { $d=$request->validate(['class_id'=>'required|exists:classes,id','subject_id'=>'required|exists:subjects,id','file'=>'required|file|mimes:csv,txt|max:2048']); $count=0; foreach(file($request->file('file')->getRealPath(),FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $i=>$line){$c=str_getcsv($line);if($i===0&&preg_match('/(english|chapter|name)/i',$line))continue;$name=trim($c[0]??'');if($name==='')continue;Chapter::create(['class_id'=>$d['class_id'],'subject_id'=>$d['subject_id'],'name'=>$name,'urdu'=>trim($c[1]??''),'is_active'=>'yes']);$count++;} return back()->with('success',$count.' chapter(s) imported successfully.'); }
    public function update(Request $request,Chapter $chapter) { $d=$request->validate(['class_id'=>'required|exists:classes,id','subject_id'=>'required|exists:subjects,id','eng_name.0'=>'required|string','urdu_name.0'=>'nullable|string']); $chapter->update(['class_id'=>$d['class_id'],'subject_id'=>$d['subject_id'],'name'=>$d['eng_name'][0],'urdu'=>$d['urdu_name'][0]??'']); return back()->with('success','Chapter updated successfully.'); }
    public function destroy(Chapter $chapter) { $chapter->delete(); return back()->with('success','Chapter deleted successfully.'); }
}
