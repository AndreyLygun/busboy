<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Dish;

class VisitController extends Controller
{
    public function about() {
        return view('visit.about');
    }
    public function menu() {
        $menu = Dish::with('dishes')->whereCategoryId(0)->orderBy('menu_index')->get();
        return view('visit.menu', compact('menu'));
    }

    public function cart() {
        $cart = session('cart', []);
        $ordered = session('ordered', []);
        return view('visit.cart', compact('cart', 'ordered'));
    }
    public function waiter() {
        return view('visit.waiter');
    }

    // Операции с корзиной
    public function cartHandler() {
        $action = request('action');
        $cart = session('cart', []);
        if ($action=='add2cart') { // добавляем блюдо
            $cart[] = ['name'=>request('name'), 'option'=>request('option'), 'price'=>request('price')];
            session(['cart'=>$cart]);
            $msg = request('name') . ' добавлен в корзину';
            return ['status'=>1, 'msg'=> $msg];
        }
        if ($action=='removeCartItem') { // убираем блюдо
            $key = request('key');
            unset($cart[$key]);
            session(['cart'=>$cart]);
        }
    }


    public function sendOrder() {
        $cart = session('cart', []);
        $ordered = session('ordered', []);
        $ordered = array_merge($ordered, $cart);
        session(['cart'=>[], 'ordered'=>$ordered]);
        return json_encode(session('cart'));
    }

    public function sendMessage() {

    }


}
