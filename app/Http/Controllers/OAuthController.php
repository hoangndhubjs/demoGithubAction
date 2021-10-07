<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Employee;
use App\Models\SystemSetting;
use Auth;
use Carbon\Carbon;
use App\Classes\Settings\SettingManager;

class OAuthController extends Controller
{
    public function redirect()
    {
        $query = http_build_query([
            'client_id' => SettingManager::getOption('SSO_BROKER_ID', config('services.sso.broker_id')),
            'redirect_uri' => url('/oauth/callback'),
            'response_type' => 'code',
            'scope' => 'view-profile'
        ]);

        return redirect(config('services.sso.url').'/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $response = Http::post(config('services.sso.url').'/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => SettingManager::getOption('SSO_BROKER_ID', config('services.sso.broker_id')),
            'client_secret' => SettingManager::getOption('SSO_BROKER_SECRET', config('services.sso.broker_secret')),
            'redirect_uri' => url('/oauth/callback'),
            'code' => $request->code
        ]);

        $body = $response->json();
        //get profile user from server
        $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' .  $body['access_token']
                ])->get(config('services.sso.url').'/api/account/get');
      
        $result = $response->json();
       
        $user = $result['data'];
       
        $this->saveUser($user);
        
        Auth::loginUsingId($user['id']);

        return redirect('/'); 
    }

    public function saveUser($user)
    {
        $findUser = Employee::find($user['id']);
      
        if(!$findUser)
            Employee::create([
                'employee_id' => rand(11,99),
                'company_id' => SettingManager::getOption('company_info_id'),
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'contact_no' => $user['phone'],
                'profile_picture' => config('services.sso.url').'/storage'.$user['profile_image'],
                'password' => bcrypt('123@@123'),
                'user_role_id' => 2,                
                'date_of_joining' => Carbon::now('Asia/Ho_Chi_Minh'),
                'is_active' => 1,
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
        else 
            $findUser->update([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'contact_no' => $user['phone'],
                'email' => $user['email'],
                'company_id' => SettingManager::getOption('company_info_id'),
                'profile_picture' => config('services.sso.url').'/storage'.$user['profile_image']
            ]);
    }

    public function onOffSSO()
    {
        if(Auth::user()->user_role_id != 1){
            return abort(404);
        }
        $query = SystemSetting::first();
        $moduleRecruitment = $query->module_recruitment;
        if ($moduleRecruitment == 'true'){
            $query->update(['module_recruitment' => '']);
        } else {
            $query->update(['module_recruitment' => 'true']);
        }
        return redirect('/');
    }
}