<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user_id;

        $findUser = Employee::where('user_id', $user_id)->first();
        if($findUser){
            $data = [];
            $data['address'] = $findUser->address;
            $data['birthday'] = $findUser->date_of_birth;
            $data['gender'] = $findUser->gender;
            $data['working'] = $findUser->company->name;
            $data['working_link'] = $findUser->company->website_url;
            return response()->json($data);
        }
    }
}