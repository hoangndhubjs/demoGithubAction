<?php

namespace App\Http\Controllers\Employees;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\QualificationEducationLevel;
use App\Models\QualificationLanguage;
use App\Repositories\QualificationRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class QualificationController extends Controller
{
    private $qualification;
    use DatatableResponseable;

    public function __construct(QualificationRepository $qualification)
    {
        $this->qualification = $qualification;
        View::share('isEmployeeModule', true);
    }

    public function qualification()
    {
        $page_title = __('xin_e_details_qualification');

        //info user
        $info_user = Auth::user();

        return view("employees.qualification.list", compact('page_title', 'info_user'));
    }

    public function datatableQualification(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('created_date');
        }
        $user_info = Auth::user();
        $listQualification = $this->qualification->getQualification($paginateConfig, $user_info->user_id);

        return $this->makeDatatableResponse($listQualification, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createFormQualification(Request $request) {

        $document_type = DocumentType::all();
        $id = $request->get('id', null);
        $qualification = null;
        $education_level = QualificationEducationLevel::all();
        $qualification_language = QualificationLanguage::all();

        if($id){
            $type = 'updated';
            $qualification = $this->qualification->find($id);
        } else {
            $type = 'created';
        }
        return view('employees.qualification.form_modal', compact('qualification', 'type', 'document_type', 'education_level', 'qualification_language'));
    }

    public function createQualification(Request $request){
//        dd($request->all());

        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'education_level_id' => 'required',
                'document_file' => ($request->id ? 'nullable' : 'required'),
            ],
            [
                'name.required' => __('xin_employee_error_sch_uni'),
                'education_level_id.required' => __('xin_employee_error_education_level'),
                'document_file.required' => __('xin_employee_error_document_file'),
            ]);

        if ($validator->passes()) {

            $data = [
                "employee_id" => Auth::id(),
                "name" => $request->name,
                "education_level_id" => $request->education_level_id,
                "majors" => $request->majors,
                "language_id" => $request->language_id,
                "from_year" => $request->from_year,
                "to_year" => $request->to_year,
                "description" => $request->description,
            ];
            if ($request->document_file) {
                $file = $request->document_file;
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/qualification', $fileName, 'public');
                $data["document_file"] = $request->document_file;
            }

            if ($id = $request->get('id')) {
                $response = $this->qualification->update($id, $data);
            } else {
                $response = $this->qualification->create($data);
            }

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);
    }

    public function delete_qualification(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->qualification->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }
}
