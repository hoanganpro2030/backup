<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\User;
use App\Products;
use App\UserCarts;
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
    public function getProduct($id){
    	if (!Auth::check()){
			return redirect()->route('signin.getSignin');
		}
		$product = DB::table('products')->where('id',$id)->first();
		$relatedPd = DB::table('products')->where('cateID',$product->cateID)->get();
		$seller = DB::table('users')->where('id',$product->sellerID)->first();
		return view('template.pages.product',compact('product','seller','relatedPd'));
    }
    public function addToCart($pid,$uid){
    	if (!Auth::check()){
			return redirect()->route('signin.getSignin');
		}
        $oldcart = DB::table('usercarts')->where('productID',$pid)->get();
        if (count($oldcart) != 0){
            DB::table('usercarts')->where('productID',$pid)->increment('quantity',1);
            return redirect()->route('order.getCart');
        }
        $cart = new UserCarts();
        $cart->userID = $uid;
        $cart->productID = $pid;
        $cart->quantity = 1;
        $cart->status = 0;
        $cart->save();
        DB::table('products')->where('id',$pid)->decrement('quantity',1);
		// $carts = DB::table('usercarts')->where('userID',$uid)->get();
  //       $seller = Products::find(1)->user;
  //       $product = DB::table('usercarts')->where('userID',$uid)->get();
        return redirect()->route('order.getCart');
    }
    public function getCart(){
        if (!Auth::check()){
            return redirect()->route('signin.getSignin');
        }
        $uid = Auth::User()->id;
        $carts = DB::table('usercarts')->where('userID',$uid)->get();
        return view('template.pages.shopping-cart',compact('carts'));
    }
     public function removeCart($id){
        if (!Auth::check()){
            return redirect()->route('signin.getSignin');
        }
        DB::table('usercarts')->where('id',$id)->delete();
        return redirect()->route('order.getCart');
     }
}
