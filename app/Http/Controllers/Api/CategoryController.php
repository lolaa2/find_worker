<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::select(['name','id'])->get();

        return response()->json([
            'data' => $categories
        ]);
    }
}
