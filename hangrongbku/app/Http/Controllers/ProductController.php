<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
class ProductController extends Controller
{
    public function getIndex(){
    	if (!Auth::check()){
			return redirect()->route('signin.getSignin');
		}
    	$products = DB::select('select * from products');
    	$categories = DB::select('select * from categories');
    	return view('template.pages.index',compact('products','categories'));
    }
    public function getCategories($id){
    	if (!Auth::check()){
			return redirect()->route('signin.getSignin');
		}
		$products = DB::table('products')->where('cateID',$id)->get();
		$categories = DB::select('select * from categories');
    	return view('template.pages.index',compact('products','categories'));
    }
}
