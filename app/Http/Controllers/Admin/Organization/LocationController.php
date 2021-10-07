<?php

namespace App\Http\Controllers\Admin\Organization;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Country;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Repositories\LocationRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Support\Facades\Validator;
use App\Models\OfficeLocation;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    use DatatableResponseable;
    private $location;

    public function __construct(LocationRepository $location)
    {
        $this->location = $location;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::all();
        $employee = Employee::all();
        $page_title = __('xin_branchs');
        return view('admin/organization/location/location', compact('page_title', 'company', 'employee'));
    }

    public function createFormAssetLocation(Request $request){
        $companies = Company::all();
        $employees = Employee::all();
        $countries = Country::all();
        $location_id = $request->location_id;
        $location = null;
        if($location_id){
            $type = 'updated';
            $location = $this->location->find($location_id)->load('company', 'employee', 'countryy');
            // dd($location);
        }else{
            $type = 'created';
        }
        return view('admin.organization.location.modal.edit_modal', compact('companies', 'type', 'countries', 'employees', 'location'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatableLists(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        // dd($paginateConfig);
        $data = $this->location->listLocation($paginateConfig, $request);
        // dd($data);
        return $this->makeDatatableResponse($data, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'location_name' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'address_1' => 'required',
                'city' => 'required',
                'state' => 'required',
            ],
            [
                'location_name.required' => __('xin_error_cat_name_field'),
                'phone.required' => __('xin_error_contact_field'),
                'email.required' => __('xin_error_cemail_field'),
                'address_1.required' => __('xin_error_address_field'),
                'city.required' => __('xin_error_city_field'),
                'state.required' => __('xin_error_state_field'),
            ]);
            // Auth::user()->tên cột => lấy cột thông tin người login mình muốn lấy
            //Auth::id() => lấy id người đang đăng nhập
            if($validator->passes()){
                $addLocation = new OfficeLocation();
                $id = $request->location_id;
                if($id !== null){
                    $location = OfficeLocation::find($id);
                    $location->fill($request->all());
                    $location['added_by'] = Auth::id();
                    $response = $location->update();
                }else{
                    $addLocation->fill($request->all());
                    $addLocation['added_by'] = Auth::id();
                    $response = $addLocation->save();
                }
                if($response === true){
                    return $this->responseSuccess(__('xin_theme_success'));
                }return $this->responseError(__('Thất bại'));
            }
            return response()->json(['errorsForm' => $validator->errors()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($id = $request->location_id) {
            $location = OfficeLocation::find($id);
                $location->delete();
        }
            return $this->responseSuccess(__('xin_theme_success'));
    }
}
