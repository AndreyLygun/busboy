<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registerUser() {
        return view('cabinet.register');
    }

    public function storeUser() {
        $attr = request()->validate([
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|min:5|max:255'
            ]
        );
        $attr['name'] = $attr['email'];
        $attr['password'] = bcrypt($attr['password']);
        $user = User::create($attr);
        auth()->login($user);
        return redirect('cabinet')->with('message', 'Новая учётная запись зарегистрирована');
    }

    public function login() {
        $attr = request()->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if (auth()->attempt($attr, request())) {
            session()->regenerate();
            return redirect(route('cabinet'))->with('message', 'Вы вошли в учётную запись');
        }
        return back()->withInput()->withErrors('email');
    }

    public function logout() {
        auth()->logout();
        return redirect('/')->with('message', 'До свидания!');
    }
}
