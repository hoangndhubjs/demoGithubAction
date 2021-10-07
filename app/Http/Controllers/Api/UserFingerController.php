<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
class UserFingerController extends Controller
{
       
    public function index(Request $request)
    {
            $user=DB::table('user_finger')->select('uid', 'name','privilege','password','group_id','user_id','card')->distinct()->get();
            foreach( $user as $u){
                if($u->password==NULL){
                    $u->password="";
                }
                $u->user_id=number_format($u->user_id);
                if($u->group_id==NULL){
                    $u->group_id="";
                }


            }
            
            return response()->json($user, Response::HTTP_OK);
    }
    
}
