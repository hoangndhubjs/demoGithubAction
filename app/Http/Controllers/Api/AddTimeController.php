<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class AddTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function indexUser(Request $request)
    {   
                $now = Carbon::now()->format('Y-m-d');
                $finger=DB::table('attendance_time_request as atime')->select('emp.employee_id')->join('employees as emp','emp.user_id', '=', 'atime.employee_id')->where('atime.is_approved',2)->where('atime.request_date',$now)->distinct()->get();
               
                foreach($finger as $fg){
                    $user=DB::table('user_finger')->where('user_id',$fg->employee_id)->update(['status'=>1]);  
                    // $user->toJson();
                    // echo $user;
                }
                
                $addtime=DB::table('user_finger')->select('uid', 'name','privilege','password','group_id','user_id','card')->where('status',1)->distinct()->get();
                foreach($addtime as $u){
                    if($u->password==NULL){
                        $u->password="";
                    }
                    $u->user_id=number_format($u->user_id);
                    if($u->group_id==NULL){
                        $u->group_id="";
                    }
    
    
                }
                
                return response()->json($addtime, Response::HTTP_OK);
    }
    
    public function indexFinger(Request $request)
    {           
                $users=DB::table('user_finger')->select('uid')->where('status',1)->get();
               
                foreach($users as $user){
                    $finger=DB::table('finger')->where('uid',$user->uid)->update(['status'=>1]);
                }
                $addtime = DB::table('finger')->select('size', 'uid','fid','valid','template')->where('status',1)->get();

                return response()->json($addtime, Response::HTTP_OK);
    }
    public function update(Request $request)
    {
        $data =$request->all();
        foreach ($data['user'] as $x){   
          
             DB::table('user_finger')->where('uid',$x['uid'])->update(['status'=>0]); 
        }
        foreach ($data['template'] as  $y){   
        
            DB::table('finger')->where('uid',$y['uid'])->update(['status'=>0]);
          
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
