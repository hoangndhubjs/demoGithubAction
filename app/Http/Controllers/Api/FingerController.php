<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
class FingerController extends Controller
{
    
    public function index(Request $request)
    {
            $finger=DB::table('finger')->select('size', 'uid','fid','valid','template')->get();

            return response()->json($finger, Response::HTTP_OK);
    }
    private function addNumberEmployeeId($employee_id)
    {
        $total_string_len_in_system = 5;
        $count_string = strlen($employee_id);
        $new_employee_id = "";
        for ($i = 0; $i < ($total_string_len_in_system - $count_string); $i++) {
            $new_employee_id .= "0";
        }
        return $new_employee_id . $employee_id;
    }
    public function update(Request $request){
        $data =$request->all();
        
        
        foreach ($data['user'] as  $x){   
            
            $employee_id=$this->addNumberEmployeeId($x['user_id']); 
          
                    DB::table('user_finger')->insert(['uid'=>$x['uid'],'user_id'=>$employee_id,'name'=>$x['name'],'privilege'=>$x['privilege'],'password'=>$x['password'],'group_id'=>$x['group_id'],'card'=>$x['card']
]);
           
        }
        foreach ($data['template'] as $y){
            
          
            DB::table('finger')->insert(['template'=>$y['template'],'size'=>$y['size'],'uid'=>$y['uid'],'fid'=>$y['fid'],'valid'=>$y['valid']]);
        }
    }
    
}
