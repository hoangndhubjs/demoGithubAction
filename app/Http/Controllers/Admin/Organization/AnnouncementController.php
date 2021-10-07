<?php

namespace App\Http\Controllers\Admin\Organization;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Company;
use App\Models\Department;
use App\Models\OfficeLocation;
use App\Repositories\AnnouncementRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Location;

class AnnouncementController extends Controller
{
    use DatatableResponseable;
    private $announcement;

    public function __construct(AnnouncementRepository $announcement)
    {
        $this->announcement = $announcement;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = __("dashboard_announcements");
        return view('admin/organization/announcement/announcement', compact('page_title'));
    }

    public function createFormAssetAnnouncement(Request $request){
        $companies = Company::all();
        $departments = Department::all();
        $locations = OfficeLocation::all();

        $announcement_id = $request->announcement_id;
        $announcement = null;
        if($announcement_id){
            $type = 'updated';
            $announcement = $this->announcement->find($announcement_id)->load('companyAsset', 'departmentAsset', 'locationAsset');
            // dd($announcement);
        }else{
            $type = 'created';
        }
        return view('admin.organization.announcement.modal.edit_modal', compact('locations', 'companies', 'departments', 'type', 'announcement'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatableLists(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        // dd($paginateConfig);
        $data = $this->announcement->listAnnouncement($paginateConfig, $request);
        // dd($data);
        return $this->makeDatatableResponse($data, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function optionLocation(Request $request){
        $id = $request->company_id;
        $location = OfficeLocation::Where('company_id',$id)->get();
        // dd($location);
        return $this->responseSuccess($location);
    }
    public function optionDepartment(Request $request){
        $id = $request->location_id;
        $department = Department::Where('location_id',$id)->get();
        // dd($location);
        return $this->responseSuccess($department);
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
                'title' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'company_id' => 'required',
                'location_id' => 'required',
                'department_id' => 'required',
                'summary' => 'required',
                'description' => 'required',
            ],
            [
                'title.required' => __('xin_error_title'),
                'start_date.required' => __('xin_error_start_date'),
                'end_date.required' => __('xin_error_end_date'),
                'summary.required' => __('xin_error_summary'),
                'description.required' => __('xin_error_task_file_description'),
            ]);
            // Auth::user()->tên cột => lấy cột thông tin người login mình muốn lấy
            //Auth::id() => lấy id người đang đăng nhập
            if($validator->passes()){
                $addAnnouncement = new Announcement();
                $id = $request->announcement_id;
                if($id !== null){
                    $announcement = Announcement::find($id);
                    $announcement->fill($request->all());
                    $announcement['published_by'] = Auth::id();
                    $response = $announcement->update();
                }else{
                    $addAnnouncement->fill($request->all());
                    $addAnnouncement['published_by'] = Auth::id();
                    $response = $addAnnouncement->save();
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
        if ($id = $request->announcement_id) {
            $announcement = Announcement::find($id);
                $announcement->delete();
        }
            return $this->responseSuccess(__('xin_theme_success'));
    }

}
