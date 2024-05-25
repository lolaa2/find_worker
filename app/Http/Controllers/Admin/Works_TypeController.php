<?php

namespace App\Http\Controllers\Admin;
use App\Models\Work;
use App\DataTables\WorksDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Works_TypeController extends Controller
{
 public function index(WorksDataTable $worksDataTable){
    return $worksDataTable->render('workstype.index');
 }
 public function create(){
    return view('workstype.create');
 }


 public function addWorkType(Request $request){
   $request->validate([
       'name'=>['required','string']        
   ]);
   $workt=Work::create([
       'name'=>$request->name
   ]);
   return back()->with('success','New Type Added Succsessfully');

 }

 public function update(Work $workt,Request $request){

   $request->validate([
       'name' => ['required','string'],
   
   ]);

   $workt->update([
       'name'=>$request->name,
   ]);

   return back()->with('success','Work Updated Successfully');

}
public function edit(Work $workt){
   return view('workstype.edit',[
       'workt'=>$workt,
     
       
   ]);
}

 public function delete (Work $workt){
   if(User::where('work_id',$workt->id)->exists()){
      return back()->with('warning','There are useres related to this type');
   }else{
         
      $workt->delete();
      return back()->with('success','New Type Added Succsessfully');
   }
 }
}
