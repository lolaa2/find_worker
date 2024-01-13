<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\DataTables\PreviousWorksDataTable;
use App\Http\Controllers\Controller;
use App\Models\Previous;
use Illuminate\Http\Request;


class PrevioussController extends Controller
{
  public function index(PreviousWorksDataTable  $previousWorksDataTable){
    return  $previousWorksDataTable-> render('previous.index');
  }
public function create(){
    return view('previous.create');
}
public function update(Previous $previous,Request $request){

  $request->validate([
      'title' => ['required','string'],
      'description' => ['required','string'],
   
  ]);

  $previous->update([
      'title'=>$request->title,
      'description'=>$request->description,
   
  ]);

  return back()->with('success','Previous Updated Successfully');

}
public function edit(Previous $previous){


  return view('previous.edit',[
      'previous'=>$previous,
    
      
  ]);
}

public function show(){
    return view('previous.show');
}
public function delete(Previous $previous){
  if(User::where('id',$previous->id)->exists()){
    return back()->with('warning','There are useres related to this type');
  }else{
  $previous->images()->delete();
  $previous->delete();
  return back();
  }
}

}
