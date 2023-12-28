<?php

namespace App\Http\Controllers\Admin;

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
public function edit(){
    return view('previous.edit');
}
public function show(){
    return view('previous.show');
}
public function delete(Previous $previous){
  
  $previous->images()->delete();
  $previous->delete();
  return back();

}

}
