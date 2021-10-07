<?php

namespace App\Http\Controllers\Employees;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\EmployeeImmigration;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ImmigrationRepository;
use Illuminate\Support\Facades\View;

class ImmigrationController extends Controller
{
    private $immigration;
    use DatatableResponseable;

    public function __construct(ImmigrationRepository $immigration)
    {
        $this->immigration = $immigration;
        View::share('isEmployeeModule', true);
    }


    public function immigration()
    {
        $page_title = __('xin_employee_immigration');

        //info user
        $info_user = Auth::user();
        $document_type = DocumentType::all();

        return view("employees.immigration.list", compact('page_title', 'info_user', 'document_type'));
    }

    public function datatableImmigration(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('created_date');
        }
        $user_info = Auth::user();
        $listImmigration = $this->immigration->getImmigration($paginateConfig, $user_info->user_id);

        return $this->makeDatatableResponse($listImmigration, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createFormImmigration(Request $request) {

        $document_type = DocumentType::all();
        $id = $request->get('id', null);
        $immigration = null;

        if($id){
            $type = 'updated';
            $immigration = $this->immigration->find($id);
        } else {
            $type = 'created';
        }
        return view('employees.immigration.form_modal', compact('immigration', 'type', 'document_type'));
    }

    public function createImmigration(Request $request) {

        $validator = Validator::make($request->all(),
            [
                'document_type_id' => 'required',
                'issue_date' => 'required|date',
                'document_file' => ($request->id ? 'nullable' : 'required').'|mimes:png,gif,jpeg,jpg|max:700',
                'place_of_issue' => 'required',
            ],
            [
                'document_type_id.required' => __('xin_employee_error_d_type'),
                'issue_date.required' => __('xin_employee_error_d_issue'),
                'document_file.required' => __('xin_employee_error_document_file'),
                'document_file.mimes' => __('xin_employee_error_document_file_mimes'),
                'document_file.max' => __('xin_employee_error_document_file_max'),
                'place_of_issue.required' => __('xin_employee_error_place_of_issue'),
            ]);

        if ($validator->passes()) {

            $data = [
                "employee_id" => Auth::id(),
                "document_type_id" => $request->document_type_id,
                "document_number" => $request->document_number,
                "issue_date" => $request->issue_date,
                "place_of_issue" => $request->place_of_issue,
            ];
            if ($request->document_file) {
                $file = $request->document_file;
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/immigration', $fileName, 'public');
                $data["document_file"] = '/storage/' . $filePath;
            }

            if ($id = $request->get('id')) {
                $response = $this->immigration->update($id, $data);
            } else {
                $response = $this->immigration->create($data);
            }

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);
    }

    public function delete_immigration(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->immigration->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

}
