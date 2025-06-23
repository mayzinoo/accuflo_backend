<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Auth;
use App\Models\Classes;
use App\Models\Category;

class ClassAndCategoryController extends BaseController {

    public function getClasses(){
        $classes=[];
        $classes=Classes::select('name','type')->get();
        return $classes;
    }
    public function getCategories(Request $request){
       $categories=[];
       $class=Classes::where('name',$request->class)->get()[0];
       $categories=Category::select('name')->where('class_id',$class->id)->get();
       return $categories;
    }
}