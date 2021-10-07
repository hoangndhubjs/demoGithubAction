<?php

namespace App\Http\Controllers\Employees;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\EmployeeBankaccount;
use App\Repositories\BankAccountRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class BankAccountController extends Controller
{
    private $bankAccount;
    use DatatableResponseable;

    public function __construct(BankAccountRepository $bankAccount)
    {
        $this->bankAccount = $bankAccount;
        View::share('isEmployeeModule', true);
    }

    public function baccount()
    {
        $page_title = __('xin_e_details_baccount');

        //info user
        $info_user = Auth::user();

        return view("employees.bank-account.list", compact('page_title', 'info_user'));
    }

    public function datatableBankAccount(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('updated_at');
        }
        $user_info = Auth::user();
        $listBankAccount = $this->bankAccount->getBankAccount($paginateConfig, $user_info->user_id);

        return $this->makeDatatableResponse($listBankAccount, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createFormBankAccount(Request $request) {
        $id = $request->get('id', null);
        $bankAccount = null;

        if($id){
            $type = 'updated';
            $bankAccount = $this->bankAccount->find($id);
        } else {
            $type = 'created';
        }
        return view('employees.bank-account.form_modal', compact('bankAccount', 'type'));
    }

    public function createBankAccount(Request $request){

        $validator = Validator::make($request->all(),
            [
                'account_title' => 'required',
                'bank_name' => 'required',
                'account_number' => 'required',
                'bank_branch' => 'required',
            ],
            [
                'account_title.required' => __('xin_acc_error_account_name_field'),
                'bank_name.required' => __('xin_employee_error_bank_name'),
                'account_number.required' => __('xin_employee_error_acc_number'),
                'bank_branch.required' => __('xin_employee_error_bank_branch'),
            ]);

        if ($validator->passes()) {

            $data = [
                "employee_id" => Auth::id(),
                "account_title" => $request->account_title,
                "is_primary" => 0,
                "bank_name" => $request->bank_name,
                "account_number" => $request->account_number,
                "bank_branch" => $request->bank_branch,
            ];

            if ($id = $request->get('id')) {
                $response = $this->bankAccount->update($id, $data);
            } else {
                $response = $this->bankAccount->create($data);
            }

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }

        }

        return response()->json(['errorsForm' => $validator->errors()]);

    }

    public function deleteBankAccount(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->bankAccount->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }
}
