<?php

namespace App\Http\Controllers\Complaint;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Repositories\ComplaintRepository;
use App\Repositories\EmployeeRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    use DatatableResponseable;

    private $complaint;

    public function __construct(ComplaintRepository $complaint)
    {
        $this->complaint = $complaint;
    }

    public function index() {
        $page_title = __('xin_complaint');
        $page_description = __('');
        $listStatus = $this->complaint->getListStatus();

        return view('pages.complaint.list', compact('page_title', 'page_description', 'listStatus'));
    }

    /**
     * list compalaint
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listComplaint(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $complaints = $this->complaint->getComplaintByUserId($paginateConfig, $request->get('status'));
        return $this->makeDatatableResponse($complaints, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    /**
     * delete compalaint
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComplaint(Request $request){
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->complaint->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

    /**
     * tạo form thêm mới và cập nhật khiếu nại
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createComplaintForm(Request $request){
        $id = $request->get('id', null);
        $employees = (new EmployeeRepository())->getNameEmployeesByCompany(Auth::user()->company_id);
        $company = (new CompanyRepository())->getCompany(Auth::user()->company_id);
        $complaint = null;
        if($id){
            $type = 'updated';
            $complaint = $this->complaint->find($id);
        } else {
            $type = 'created';
        }
        return view('pages.complaint.form_modal', compact('complaint', 'type', 'employees', 'company'));
    }

    /**
     * Cập nhận và tạo mới khiếu nại
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateComplaint(Request $request){

        $data = array(
            'complaint_from' => $request->complaint_from,
            'company_id' => $request->company_id,
            'title' => $request->title,
            'description' => $request->description ?? '',
            /*'attachment' => $fname,*/
            'complaint_date' => date('Y-m-d'),//($request->complaint_date)?date('Y-m-d', strtotime($request->complaint_date)):'',
            'complaint_against' => $request->complaint_against,
            'status' => 0,
        );

        if($request->file()) {
            $fileName = 'complaints_'.time().'_'.$request->file('attachment')->getClientOriginalName();
            $filePath = $request->file('attachment')->storeAs('uploads/complaints', $fileName, 'public');
            if($filePath){
                $data['attachment'] = 'complaints_'.time().'_'.$request->file('attachment')->getClientOriginalName();
            }
        }

        if ($id = $request->get('id')) {
            $complaint = $this->complaint->update($id, $data);
        } else {
            $complaint = $this->complaint->create($data);
        }
        return $this->responseSuccess($complaint);
    }
}
