<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WorkersDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkersController extends Controller
{
    public function index(WorkersDataTable $workersDataTable){
        return $workersDataTable->render('workers.index');
    }
    public function create(){
        return view("workers.create");
    }

    public function edit(){
        return view("workers.edit");
    }

    public function show(){
        return view("workers.show");
    }
}