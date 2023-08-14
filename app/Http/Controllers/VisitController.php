<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public  function info() {

    }

    public function menu() {
        $books = Book::with('categories.dishes')->get();
        return view('visit.menu', ['books' => $books]);
    }

    public function cart() {
        return view('visit.cart');
    }

    public function waiter() {
        return view('visit.waiter');
    }
}
