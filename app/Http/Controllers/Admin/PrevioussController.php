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

public function addPrevious( Previous $previous,Request $request){
  $request->validate([
      'description'=>['required','string'],
      'title'=>['required','string']         
  ]);
  $previous=Previous::create([
      'description'=>$request->description,
      'title'=>$request->title,
     
  ]
); 

  return back()->with('success','New Previous work Added Succsessfully');

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
      'title'=>$previous->title
      
  ]);
}

public function show(Previous $previous){
    return view('previous.show',[
      'previous'=>$previous
    ]);
    
}
public function delete(Previous $previous){
  if(User::where('id',$previous->id)->exists()){
    return back()->with('warning','There are useres related to this type');
  }else{
  $previous->images()->delete();
  $previous->delete();
  return back()->with('success','Previous deleted successfuly');
  }
}

}
