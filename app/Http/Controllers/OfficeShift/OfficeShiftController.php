<?php

namespace App\Http\Controllers\OfficeShift;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DatatableResponseable;
use App\Repositories\OfficeShiftRepository;
use App\Classes\PaginateConfig;
use App\Models\Company;
class OfficeShiftController extends Controller
{
    use DatatableResponseable;

    private $officeShift;
    public function __construct(OfficeShiftRepository $officeShift)
    {
        $this->officeShift = $officeShift;
    }

    public function index()
    {
        $page_title = __('left_office_shifts');
        return view('pages.office_shift.list', compact('page_title'));
    }
    
    public function listOfficeShift(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('create_date');
        }
        $officeShift = $this->officeShift->getOfficeShift($paginateConfig);
        return $this->makeDatatableResponse($officeShift, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function addOfficeShiftForm(Request $request)
    {
        $id = $request->get('id', null);
        $company = Company::get();
        $office_shift = null;
        if ($id) {
            $office_shift = $this->officeShift->find($id);
        }
        return view('pages.office_shift.form_modal', compact('office_shift', 'company'));
    }
    
    public function addOfficeShift(Request $request) {
        $rules = array(
            'shift_name' => 'required',
        );
        $messages = array(
            'required' => __('field_required'),
        );
        $request->validate($rules, $messages);

        $data = array(
            'company_id' => $request->company_id,
            'shift_name' => $request->shift_name,

            'monday_in_time' => $request->monday_in_time ? date('H:i',strtotime($request->monday_in_time)) : '',
            'monday_out_time' => $request->monday_out_time ? date('H:i',strtotime($request->monday_out_time)) : '',

            'tuesday_in_time' => $request->tuesday_in_time ? date('H:i',strtotime($request->tuesday_in_time)) : '',
            'tuesday_out_time' => $request->tuesday_out_time ? date('H:i',strtotime($request->tuesday_out_time)) : '',

            'wednesday_in_time' => $request->wednesday_in_time ? date('H:i',strtotime($request->wednesday_in_time)) : '',
            'wednesday_out_time' => $request->wednesday_out_time ? date('H:i',strtotime($request->wednesday_out_time)) : '',

            'thursday_in_time' => $request->thursday_in_time ? date('H:i',strtotime($request->thursday_in_time)) : '',
            'thursday_out_time' => $request->thursday_out_time ? date('H:i',strtotime($request->thursday_out_time)) : '',

            'friday_in_time' => $request->friday_in_time ? date('H:i',strtotime($request->friday_in_time)) : '',
            'friday_out_time' => $request->friday_out_time ? date('H:i',strtotime($request->friday_out_time)) : '',

            'saturday_in_time' => $request->saturday_in_time ? date('H:i',strtotime($request->saturday_in_time)) : '',
            'saturday_out_time' => $request->saturday_out_time ? date('H:i',strtotime($request->saturday_out_time)) : '',

            'sunday_in_time' => $request->sunday_in_time ? date('H:i',strtotime($request->sunday_in_time)) : '',
            'sunday_out_time' => $request->sunday_out_time ? date('H:i',strtotime($request->sunday_out_time)) : '',
        );
        if ($id = $request->get('id')) {
            $this->officeShift->update($id, $data);
            return $this->responseSuccess(__('Cập nhật thành công'));
        } else {
            $order = $this->officeShift->create($data);
            return $this->responseSuccess(__('Thêm thành công'));
        }
    }

    public function deleteOfficeShift(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->officeShift->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

    public function updateDefaultShift(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        $getDefaultShift = $this->officeShift->getDefaultShift();
        if($getDefaultShift) {
            $query = $getDefaultShift->update(['default_shift' => 0]);
        }
        if ($getDefaultShift && $query) {
            $data = array(
                'default_shift' => 1,
            );
            $this->officeShift->update($id, $data);
            return $this->responseSuccess(__("xin_success_shift_default_made"));
        }
        return $this->responseError(__("cập nhập thất bại"));
    }

    public function listOfficeShiftByCompany(Request $request){
        $company_id = trim($request->company_id);
        $term = trim($request->q);
        $formatted_tags = [];
        if($company_id){
            if (empty($term)) {
                $officeShifts = $this->officeShift->getOfficeShiftByCompany($company_id);
            } else {
                $officeShifts = $this->officeShift->getOfficeShiftByCompany($company_id, $term);
            }
            foreach ($officeShifts as $officeShift) {
                $formatted_tags[] = ['id' => $officeShift->office_shift_id, 'text' => $officeShift->shift_name];
            }
        }

        return \Response::json($formatted_tags);
    }

}
