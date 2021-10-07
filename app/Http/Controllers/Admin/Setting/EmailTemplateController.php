<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Repositories\EmailTemplateRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class EmailTemplateController extends Controller
{
    private $emailTemplate;
    use DatatableResponseable;

    public function __construct(EmailTemplateRepository $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
        View::share('adminSetting', true);
    }

    public function index() {
        $page_title = __('Mẫu email');

        return view('admin.email-template.index', compact('page_title'));
    }

    public function datatableEmailTemplate(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('created_at');
        }
        $listBankAccount = $this->emailTemplate->getEmailTemplate($paginateConfig);

        return $this->makeDatatableResponse($listBankAccount, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createTemplateForm(Request $request) {

        $id = $request->get('id', null);
        $emailTemplate = null;

        if($id){
            $type = 'updated';
            $emailTemplate = $this->emailTemplate->find($id);
        } else {
            $type = 'created';
        }
        return view('admin.email-template.form_modal', compact('emailTemplate', 'type'));
    }

    public function updateTemplate(Request $request) {

        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'subject' => 'required',
                'message' => 'required',
            ],
            [
                'name.required' => __('xin_error_cat_name_field'),
                'subject.required' => __('xin_error_subject_field'),
                'message.required' => __('xin_project_message'),
            ]);

        if ($validator->passes()) {

            $data = [
                "name" => $request->name,
                "subject" => $request->subject,
                "message" => $request->message,
                "status" => $request->status,
            ];
            if ($id = $request->get('id')) {
                $response = $this->emailTemplate->update($id, $data);
            } else {
                $response = $this->emailTemplate->create($data);
            }

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }

        }

        return response()->json(['errorsForm' => $validator->errors()]);

    }
}
