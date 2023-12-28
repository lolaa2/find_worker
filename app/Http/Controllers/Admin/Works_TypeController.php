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
 public function edit(){
    return view('workstype.edit');
 }
 public function show(){
    return view('workstype.show');
 }
 public function delete (Work $id){
   if(User::where('work_id',$id->id)->exists()){
      return back()->with('warning','There are useres related to this type');
   }else{
         
      $id->delete();
      return back();
   }
 }
}
