<?php
namespace App\Http\Controllers\Admin\Academics;
use App\Models\Academics\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class SubjectController extends BaseAcademicsController
{
    public function index(Request $request): View{$records=Subject::when($request->filled('search'),fn($q)=>$q->where(fn($q)=>$q->where('name','like','%'.$request->string('search').'%')->orWhere('code','like','%'.$request->string('search').'%')->orWhere('type','like','%'.$request->string('search').'%')))->orderBy('id')->paginate(20)->withQueryString();$subject=$request->integer('edit')?Subject::findOrFail($request->integer('edit')):null;return view('admin.academics.curriculum.subjects',compact('records','subject'));}
    public function store(Request $request){$d=$request->validate(['name'=>'required|string|max:100|unique:subjects,name','code'=>'required|string|max:100|unique:subjects,code','type'=>['required',Rule::in(['theory','practical'])]]);Subject::create($d);return back()->with('success','Subject added successfully.');}
    public function update(Request $request,Subject $subject){$d=$request->validate(['name'=>['required','string','max:100',Rule::unique('subjects','name')->ignore($subject->id)],'code'=>['required','string','max:100',Rule::unique('subjects','code')->ignore($subject->id)],'type'=>['required',Rule::in(['theory','practical'])]]);$subject->update($d);return to_route('admin.academics.subjects.index')->with('success','Subject updated successfully.');}
    public function destroy(Subject $subject){$subject->delete();return to_route('admin.academics.subjects.index')->with('success','Subject deleted successfully.');}
}
