<?php

namespace App\Http\Controllers\Admin\Organization;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Country;
use App\Models\Currency;
use App\Repositories\SettingRepository;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use App\Classes\PaginateConfig;
use App\Traits\DatatableResponseable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
     use DatatableResponseable;

    private $setting;
    private $company;

    public function __construct(SettingRepository $setting, CompanyRepository $company)
    {
        $this->setting = $setting;
        $this->company = $company;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(123122);
        $page_title = __("left_company");
        $company_type = CompanyType::all();
        $company = Company::all();
        // dd($timezones);
        return view('admin.organization.company.company', compact('page_title', 'company_type', 'company'));
    }

    public function datatableLists(Request $request)
    {
        $paginateConfig = PaginateConfig::fromDatatable($request); // so luong duoc hien thi tren 1 trang
        $data = $this->company->listCompany($paginateConfig, $request); // lay tat ca company va phan trang
        return $this->makeDatatableResponse($data, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir()); // tra ve du lieu + order by du lieu
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFormAssetCompany(Request $request)
    {
        $company_type = CompanyType::all();
        $country = Country::all();
        $currency = Currency::all();
        $timezones = $this->setting->all_timezones();

        $id = $request->id;
        $company = null;
        if ($id) {
            // dd($id);
            $type = 'updated';
            $company = $this->company->find($id)->load('countries', 'company_type');
        }else{
            $type = 'created';
        }
        return view('admin.organization.company.modal.edit_modal', compact('company', 'type', 'company_type', 'country', 'currency', 'timezones'));
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
        // dd($request->all());
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'password' => 'required|min:6',
                'contact_number' => 'required',
                'email' => ['required', Rule::unique('companies', 'email')->ignore($request->company_id,'company_id')],
                'username' => ['required', Rule::unique('companies', 'username')->ignore($request->company_id,'company_id')],
                'website_url' => 'required',
                'government_tax' => 'required',
                'address_1' => 'required',
                'city' => 'required',
                'state' => 'required',
            ],
            [
                'name.required' =>__('xin_error_cat_name_field'),
                'username.required' => __('xin_employee_error_username'),
                'username.unique' => __('xin_employee_username_login_already_exist'),
                'password.required' => __('xin_employee_error_password'),
                'password.min' => __('xin_employee_error_password_least'),
                'contact_number.required' => __('xin_error_contact_field'),
                'email.required' => __('xin_error_cemail_field'),
                'email.unique' =>  __('xin_employee_email_already_exist'),
                'website_url.required' => __('xin_error_website_field'),
                'government_tax.required' => __('xin_error_gtax_field'),
                'address_1.required' => __('xin_error_address_field'),
                'city.required' => __('xin_error_city_field'),
                'state.required' => __('xin_error_state_field'),
            ]);

        if ($validator->passes()) {
        // dd($request->all());
            $has_file = $request->hasFile('logo');
            $addcom = new Company;
            $array = [
                        'name' => $request->name,
                        'type_id' => $request->type_id,
                        'trading_name' => $request->trading_name,
                        'username' => $request->username,
                        'password' => $request->password,
                        'registration_no' => $request->registration_no,
                        'government_tax' => $request->government_tax,
                        'email' => $request->email,
                        'contact_number' => $request->contact_number,
                        'website_url' => $request->website_url,
                        'address_1' => $request->address_1,
                        'address_2' => $request->address_2,
                        'city' => $request->city,
                        'state' => $request->state,
                        'zipcode' => $request->zipcode,
                        'country' => $request->country,
                        'default_currency' => $request->default_currency,
                        'default_timezone' => $request->default_timezone,
                    ];
                if (null !== $id = $request->company_id) {
                    $com = Company::find($id);
                    if($has_file){
                        $com->fill($request->all());
                        $get_img = $request->file('logo');
                        $get_img_name = $get_img->getClientOriginalName();
                        $new_img = uniqid() . '_' . str_replace('','_',$get_img_name);
                        $get_img->move('uploads/company', $new_img);
                        $com['logo'] = $new_img;
                        $com['added_by'] = Auth::id();
                    }else{
                        $com->fill($array);
                        $com['added_by'] = Auth::id();
                    }
                    $reponse = $com->update();
                }else {
                    // dd($request->all());
                    $addcom->fill($request->all());
                    if ($has_file) {
                        $get_img = $request->file('logo');
                        $get_img_name = $get_img->getClientOriginalName();
                        $new_img = uniqid() . '_' . str_replace('','_', $get_img_name);
                        $get_img->move('uploads/company', $new_img);
                        $addcom['logo'] = $new_img;
                        $com['added_by'] = Auth::id();
                    }
                    $reponse = $addcom->save();
                }
                if(true === $reponse) {
                    return $this->responseSuccess(__('xin_theme_success'));
                }
                return $this->responseError(__('Thất bại'));
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
        if ($id = $request->id) {
            $com = Company::find($id);
                $com->delete();
        }
            return $this->responseSuccess(__('xin_theme_success'));
    }
}
