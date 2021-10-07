<?php

namespace App\Http\Controllers\Employees;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\EmployeeWorkExperience;
use App\Repositories\ExperienceRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ExperienceController extends Controller
{
    private $experience;
    use DatatableResponseable;

    public function __construct(ExperienceRepository $experience)
    {
        $this->experience = $experience;
        View::share('isEmployeeModule', true);
    }

    public function experience()
    {
        $page_title = __('xin_e_details_w_experience');

        //info user
        $info_user = Auth::user();

        return view("employees.experience.list", compact('page_title', 'info_user'));
    }



    public function datatableExperience(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('created_at');
        }
        $user_info = Auth::user();
        $listExperience = $this->experience->getExperience($paginateConfig, $user_info->user_id);

        return $this->makeDatatableResponse($listExperience, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createFormExperience(Request $request) {

        $id = $request->get('id', null);
        $experience = null;

        if($id){
            $type = 'updated';
            $experience = $this->experience->find($id);
        } else {
            $type = 'created';
        }
        return view('employees.experience.form_modal', compact('experience', 'type'));
    }

    public function createExperience(Request $request) {

        $validator = Validator::make($request->all(),
            [
                'company_name' => 'required',
                'from_date' => 'required|date|before:to_date',
                'to_date' => 'required|date|after:from_date',
                'description' => 'required',
            ],
            [
                'company_name.required' => __('xin_employee_error_company_name'),
                'from_date.required' => __('xin_employee_error_from_date'),
                'from_date.before' => __('xin_employee_error_from_date_before'),
                'to_date.required' => __('xin_employee_error_to_date_required'),
                'to_date.after' => __('xin_employee_error_to_date_after'),
                'description.required' => __('xin_error_task_file_description'),
            ]);

        if ($validator->passes()) {

            $data = [
                "employee_id" => Auth::id(),
                "company_name" => $request->company_name,
                "from_date" => $request->from_date,
                "to_date" => $request->to_date,
                "description" => $request->description,
            ];

            if ($id = $request->get('id')) {
                $response = $this->experience->update($id, $data);
            } else {
                $response = $this->experience->create($data);
            }

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);

    }

    public function delete_experience(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->experience->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }
}
