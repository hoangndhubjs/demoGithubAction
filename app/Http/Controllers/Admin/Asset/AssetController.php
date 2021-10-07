<?php

namespace App\Http\Controllers\Admin\Asset;

use App\Classes\PaginateConfig;
use App\Models\Employee;
use App\Repositories\AssetRepository;
use App\Traits\DatatableResponseable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AssetsCategories;
use App\Repositories\CompanyRepository;
use App\Repositories\AssetImageRepository;
use App\Models\Asset;
use App\Repositories\AssetWarrantyRepository;
use App\Models\AssetHistory;
class AssetController extends Controller
{
    use DatatableResponseable;
    private $asset;
    private $company;
    private $asset_image;
    private $asset_warranty;

    public function __construct(AssetRepository $asset, CompanyRepository $company, AssetImageRepository $asset_image, AssetWarrantyRepository $asset_warranty){
        $this->asset = $asset;
        $this->company = $company;
        $this->asset_image = $asset_image;
        $this->asset_warranty = $asset_warranty;
    }
    // show list asset
    public function list_asset(){
        $page_title = __('list_asset');
        $categoryAsset = AssetsCategories::all();
        return view('admin.asset.list_asset.list_asset', compact('page_title','categoryAsset'));
    }
    public function listDatatableAsset(Request $request){
        $paginateConfig = PaginateConfig::fromDatatable($request);
        $orders = $this->asset->listAssets($paginateConfig, $request);
        return $this->makeDatatableResponse($orders, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }
    /**
     * tạo form thêm mới tài sản
     */
    public function createFormAsset(Request $request){
        $id = $request->get('id', null);
        $asset = null;
        $info_asset = array();
        $detail_asset = array();
        $asset =  $id ? $this->asset->find($id) : '';
        $type = $request->type;
        $title_module = '';
        if ($type == 'warranty'){
            $view_blade = 'add_asset';
            $title_module = __('Chỉnh sửa lịch sử bảo hành');
        }elseif($type == 'detail'){
            $detail_asset = array(
                'warranty' => $this->asset_warranty->getWarranty($id),
                'asset' => $this->asset->find($id),
                'asset_history' => AssetHistory::with('employee')
                    ->where('asset_id', $id)
                    ->latest('id')
                    ->get()
            );
//            dd($detail_asset);
            $view_blade = 'detail';
        }else{
            $title_module =  $asset ? __('update_info_asset') : __('add_new_asset');

            $asset && $type = 'update';
            $asset && $info_asset = array(
                'date' => date('d-m-Y', strtotime($asset->date_add_asset)),
                'warranty_date' => date('d-m-Y', strtotime($asset->warranty_end_date)),
                'image_asset' => $this->asset_image->getImageAsset($id),
            );
            $view_blade = 'add_asset';
//            dd($asset);
        }
        $categoryAsset = AssetsCategories::all();
        $all_companies = $this->company->get_all_companies();
        return view('admin.asset.list_asset.form_asset.'.$view_blade,
            compact('categoryAsset','all_companies','asset','info_asset','type','id','detail_asset','title_module'));
    }
    /**
     * thêm mới tài sản
     */
    public function addOrEditAsset(Request $request){

        if ($request->warranty_end_date){
            $dates = Carbon::createFromFormat('d-m-Y', $request->warranty_end_date);
            $dates2 = Carbon::createFromFormat('d-m-Y', $request->date_add_asset);
            $date = $dates->format('Y-m-d');
            $dates2 = $dates2->format('Y-m-d');
        }
        $data = array(
              "assets_category_id" =>   $request->assets_category_id,
              "company_asset_code" =>   $request->company_asset_code,
              "name"               =>   $request->name,
              "is_working"         =>   $request->is_working,
              "price"              =>   $request->price ? $request->price : 0,
              "invoice_number"     =>   $request->invoice_number ? $request->invoice_number : 0,
              "company_id"         =>   $request->company_id ? $request->company_id : 0,
              "employee_id"        =>   $request->employee_id ? $request->employee_id : 0,
              "manufacturer"       =>   $request->manufacturer ? $request->manufacturer : 0,
              "serial_number"      =>   $request->serial_number ? $request->serial_number : 0,
              "warranty_end_date"  =>   $request->warranty_end_date ? $date : '',
              "age_life_asset"     =>   $request->age_life_asset ? $request->age_life_asset : 0,
              "asset_note"         =>   $request->asset_note ? $request->asset_note : 0,
        );

        if ($asset_id = $request->assets_id){
            $add_asset = Asset::where('assets_id', $asset_id)->update($data);
            if ($request->user_id_had !== $request->employee_id){
                $find_employee = Employee::find($request->employee_id);
                $date_history = $dates2 ? $dates2 : date('Y-m-d');
                $data = array(
                    'asset_id' => $asset_id,
                    'employee_id' => $request->employee_id,
                    'company_id' => $request->company_id,
                    'department_id' => $find_employee->department_id ? $find_employee->department_id : 0,
                    'start_date' => $date_history,
                    'end_date' => $date_history,
                );
                $result = AssetHistory::create($data);
            }
        }else{
            $add_asset = $this->asset->create($data);
        }
        if ($add_asset){
            if ($request->asset_image){
                $assetID = $request->assets_id ?? $add_asset->assets_id;
                $file = $request->asset_image;
                foreach ($file as $key => $image){
                    $fileName = time() . '_' . $file[$key]->getClientOriginalName();
                    $filePath = $file[$key]->storeAs('asset_image', $fileName, 'public');
                    $asset_image = '/storage/' . $filePath;
                    $data_image = array(
                        'assets_id' => $assetID,
                        'employee_id' => $request->employee_id,
                        'asset_image' => $fileName
                    );
                    $this->asset_image->create($data_image);
                }
            }
            return $this->responseSuccess(__('xin_theme_success'), 200);
        }else{
            return $this->responseError(__("update_error"));
        }
    }
    /**
     * xóa tài sản
     */
    public function deleteAsset(Request $request){
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->asset->delete($id)) {
            $this->asset->deleteAssetImage($id);
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }
    /**
     * Thêm mới lịch sử bảo hành
     */
    public function warrantyAssetHistory(Request $request){
        $warranty_start = Carbon::createFromFormat('d-m-Y', $request->warranty_start);
        $warranty_end = $request->warranty_end ? Carbon::createFromFormat('d-m-Y', $request->warranty_end) : '';
        $data = array(
            'asset_id' => $request->asset_id,
            'warranty_start' => $warranty_start->format('Y-m-d'),
            'warranty_end' => $warranty_end ? $warranty_end->format('Y-m-d') : '',
            'warranty_note' => $request->warranty_note,
        );
        $create = $this->asset_warranty->create($data);
        if ($create){
            return $this->responseSuccess(__('xin_theme_success'), 200);
        }else{
            return $this->responseError(__("update_error"));
        }
    }
    /**
     * xóa hình ảnh tài sản
     */
    public function deleteAssetImage(Request $request)
    {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        $delete = $this->asset_image->deleteEachAssetImage($id);
        if ($delete) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }
}
