<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            return redirect('/');
        }
        if(app('hrm')->isSSO()) {
            return redirect('/oauth/redirect');
        }
        $page_title = __("Đăng nhập");
        return view("pages.login.login", compact('page_title'));
    }

    public function login(Request $request) {
        $credential= [
            'email' => $request->get('username'),
            'password' => $request->get('password')
        ];
        if (Auth::attempt($credential)) {
            return redirect('/');
        }
        return redirect()->back()->with('alert_errors', [__("Wrong Username/Password!")]);
    }

    public function logout() {
        Auth::logout();
        if(app('hrm')->isSSO()) {
            return redirect()->away(config('services.sso.url').'/logoutClient');
        }
        return redirect()->route('login');
    }

    public function reset_pass_hard(Request $request) {
        $email = $request->get('email');
        $passs = Hash::make('chaoban');
        \App\Models\Employee::where('email',$email)->update(['password'=>$passs]);
        dd($email);
    }
}
