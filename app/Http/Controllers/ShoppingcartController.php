<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use MongoDB\Driver\Session;

class ShoppingcartController extends Controller
{
    public static $menu_parent = 'shopping-cart';
    public function show(){
        if (Session::has('shoppingCart')){
            $shoppingCart = Session::get('shoppingCart');
        }else{
            $shoppingCart = [];
        }
        return view('cart',
            ['shoppingcart' => `$shoppingCart`]);
    }

    public function add(Request $request){
    $productId = $request->get('id');
    $productQuantitiy = $request->get('quality');
    if($productQuantitiy <= 0){
        return view('admin.errors.404', [
            'mgs' => 'So luong sp lon hon 0',
            'menu_parent' => self::$menu_parent,
            'menu_action' => 'creat'
        ]);
    }
    $obj = Product::find($productId);
    if ($obj == null){
        return view('admin.errors.404',[
            'mgs' => '0 tim thay sp',
            'menu_parent' => self::$menu_parent,
            'menu_action' => 'creat'
        ]);
    }
    $shoppingCart = null;
    if (Session::has('shoppingCart')){
        $shoppingCart = Session::get('shoppingCart');
    }else{
        $shoppingCart =[];
    }
    if (array_key_exists($productId,$shoppingCart)){
        $existingCartItem = $shoppingCart[$productId];
        $existingCartItem->quantity += $productQuantitiy;
        $shoppingCart[$productId] = $existingCartItem;
    }else{
        $cartItem = new stdClass();
        $cartItem-> id = $obj -> id;
        $cartItem-> name = $obj -> name;
        $cartItem-> unprice = $obj -> price;
        $shoppingCart[$productId] = $cartItem;
    }
    Session::put('shoppingcart', $shoppingCart);
    return redirect('/cart/show');
    }

    public function update(Request $request)
    {
        $productId = $request->get('id');
        $productQuantity = $request->get('quantity');
        if ($productQuantity <= 0) {
            return view('admin.errors.404', [
                'mgs' => 'so luong sp phai > 0',
                'menu_parent' => self::$menu_parent,
                'menu_action' => 'creat'
            ]);
        }
        $obj = Product::find($productId);
        if ($obj == null) {
            return view('admin.errors.404', [
                'mgs' => 'Ko tim thay sp',
                'menu_parent' => self::$menu_parent,
                'menu_action' => 'creat'
            ]);
        }

        if (Session::has('shoppingCart')) {
            $shoppingCart = Session::get('shoppingCart');
        }else{
            $shoppingCart = [];
        }
        if (array_key_exists($productId,$shoppingCart)){
            $existingCart = $shoppingCart[$productId];
            $existingCart -> quantity = $productQuantity;
            $shoppingCart[$productId] = $existingCart;
        }
        Session::put('shoppingCart',$shoppingCart);
        return redirect('/cart/show');
    }

    public function remove(Request $request){
        $productId = $request -> get('id');
        if (Session::has('shoppingCart')){
            $shoppingCart = Session::get('soppingCart');
        }else{
            $shoppingCart = [];
        }
        unset($shoppingCart[$productId]);
        Session::put('shoppingCart', $shoppingCart);
        return redirect('/cart/show');
    }

}
