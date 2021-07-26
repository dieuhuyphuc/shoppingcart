<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShoppingcartController extends Controller
{
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
}
}
