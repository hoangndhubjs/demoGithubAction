<?php

namespace App\Http\Controllers;

use App\Theme\Theme;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    private $responseStructure = [
        'success' => false
    ];
    
    public function responseSuccess($data, $http_code = 200) {
        $response = $this->responseStructure;
        $response['success'] = true;
        $response['data'] = $data;
        return response()->json($response, $http_code);
        
    }
    public function responseError($errors, $http_code = 500) {
        $response = $this->responseStructure;
        $response['success'] = false;
        $response['errors'] = $errors;
        return response()->json($response, $http_code);
    }
    
}
