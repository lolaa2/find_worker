<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoreisDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class CategoreisController extends Controller
{
    public function index(CategoreisDataTable $categoreisDataTable){
        return $categoreisDataTable->render('cities.index');
    }
    


    public function update(Category $cate,Request $request){

        $request->validate([
            'name' => ['required','string'],
        
        ]);
     
        $cate->update([
            'name'=>$request->name,
        ]);
     
        return back()->with('success','City Updated Successfully');
     
     }
     public function edit(Category $cate){
        
        return view('cities.edit',[
            'cate'=>$cate,
          
            
        ]);
     }





public function delete(Category $cate){
    if(Service::where('category_id',$cate->id)->exists()){
      return back()->with('warning','There are Services related to this category');
    }else{
    $cate->delete();
    return back();
    }


}}


