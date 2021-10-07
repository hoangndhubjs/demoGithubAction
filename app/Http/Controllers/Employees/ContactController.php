<?php

namespace App\Http\Controllers\Employees;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\EmployeeContact;
use App\Repositories\ContactRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ContactController extends Controller
{
    private $contact;
    use DatatableResponseable;

    public function __construct(ContactRepository $contact)
    {
        $this->contact = $contact;
        View::share('isEmployeeModule', true);
    }

    public function contact()
    {
        $page_title = __('xin_employee_emergency_contacts');

        //info user
        $info_user = Auth::user();
        return view("employees.contact.list", compact('page_title', 'info_user'));
    }

    public function datatableContact(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('created_at');
        }
        $user_info = Auth::user();
        $listContact = $this->contact->getContact($paginateConfig, $user_info->user_id);

        return $this->makeDatatableResponse($listContact, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createFormContact(Request $request) {

        $id = $request->get('id', null);
        $contact = null;

        if($id){
            $type = 'updated';
            $contact = $this->contact->find($id);
        } else {
            $type = 'created';
        }
        return view('employees.contact.form_modal', compact('contact', 'type'));
    }

    public function createContact(Request $request) {

        $validator = Validator::make($request->all(),
            [
                'contact_name' => 'required',
                'relation' => 'required',
                'mobile_phone' => 'required',
                'work_email' => 'required|email',
            ],
            [
                'contact_name.required' => __('xin_employee_error_full_name'),
                'relation.required' => __('xin_employee_error_relation'),
                'mobile_phone.required' => __('xin_employee_error_mobile_phone'),
                'work_email.required' => __('xin_employee_error_email'),
                'work_email.email' => __('xin_employee_error_invalid_email'),
            ]);

        if ($validator->passes()) {
            $data = [
                "employee_id" => Auth::id(),
                "contact_name" => $request->contact_name,
                "relation" => $request->relation,
                'is_primary' => $request->is_primary ?? null,
                'is_dependent' => $request->is_dependent ?? null,
                "mobile_phone" => $request->mobile_phone,
                "work_email" => $request->work_email,
                "address_1" => $request->address_1,
            ];

            if ($id = $request->get('id')) {
                $response = $this->contact->update($id, $data);
            } else {
                $response = $this->contact->create($data);
            }

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);

    }

    public function delete_contact(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->contact->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

}
