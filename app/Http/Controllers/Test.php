<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class Test extends Controller
{
    public function index() {
        dd('hehehe');
    }
    
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }
}