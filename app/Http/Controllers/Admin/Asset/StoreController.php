<?php

namespace App\Http\Controllers\Admin\Asset;

use App\Classes\PaginateConfig;
use App\Http\Controllers\Controller;
use App\Repositories\AssetCategoryRepository;
use App\Traits\DatatableResponseable;
use Illuminate\Http\Request;
use App\Models\AssetsCategories;
use App\Models\Asset;
use App\Models\DetailAssets;
use App\Models\AssetsDetailAssets;
use App\Models\HrsaleModuleAttribute;
use App\Models\Company;
use App\Models\Employee;
use App\Models\AssetHistory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    use DatatableResponseable;
    private $assetCategory;
    public function __construct(AssetCategoryRepository $assetCategory)
    {
        $this->assetCategory = $assetCategory;
    }

    public function listCategory() {
        $page_title = __('danh mục tài sản');

        return view('admin.asset.category.index', compact('page_title'));
    }

    public function datatableAssetCategory(Request $request) {
        $paginateConfig = PaginateConfig::fromDatatable($request);
        if (!$paginateConfig->getSortColumn()) {
            $paginateConfig->setSortBy('created_date');
        }
        $listImmigration = $this->assetCategory->getAssetCategory($paginateConfig);

        return $this->makeDatatableResponse($listImmigration, $paginateConfig->getSortColumn(), $paginateConfig->getSortDir());
    }

    public function createFormAssetCategory(Request $request) {

        $id = $request->get('id', null);
        $assetCategory = null;

        if($id){
            $type = 'updated';
            $assetCategory = $this->assetCategory->find($id);
        } else {
            $type = 'created';
        }
        return view('admin.asset.category.form_modal', compact('assetCategory', 'type'));
    }

    public function createAssetCategory(Request $request) {

        $validator = Validator::make($request->all(),
            [
                'category_name' => 'required',
            ],
            [
                'category_name.required' => __('xin_error_cat_name_field'),
            ]);

        if ($validator->passes()) {

            $data = [
                "category_name" => $request->get('category_name'),
            ];

            if ($id = $request->get('id')) {
                $response = $this->assetCategory->update($id, $data);
            } else {
                $response = $this->assetCategory->create($data);
            }

            if ($response == true) {
                return $this->responseSuccess(__('xin_theme_success'));
            } else {
                return $this->responseError(__('Thất bại'));
            }
        }

        return response()->json(['errorsForm' => $validator->errors()]);
    }

    public function deleteAssetCate(Request $request) {
        if (!$id = $request->get('id')) {
            return $this->responseError(__("nothing_to_delete"));
        }
        if ($this->assetCategory->delete($id)) {
            return $this->responseSuccess(__("delete_success"));
        }
        return $this->responseError(__("delete_fail"));
    }

    public function list()
    {
        $page_title = __('Quản lý kho');
        $total_asset_cate = $this->getDataChart();
        return view('admin.asset.store.index', compact('page_title', 'total_asset_cate'));
    }

    public function formStoreAsset()
    {
        $asset_category = AssetsCategories::get();
        return view('admin.asset.store.form_add_asset', compact('asset_category'));
    }

    public function formStoreAuthority()
    {
        $company = Company::get();
        $assets_not_working = $this->get_all_assets_not_working();
        return view('admin.asset.store.form_authority', compact('assets_not_working', 'company'));
    }

    public function findAssets(Request $request)
    {
        $category_id = $request->category_id;
        $result = Asset::where('assets_category_id', $category_id)->get();
		if($result->count() > 0) {
            return response()->json($result);
		}
    }

    public function addStore(Request $request)
    {
        $rules = array(
            'asset_name' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'detail_assets' => 'required',
        );
        $messages = array(
            'required' => __('field_required'),
        );
        $request->validate($rules, $messages);

        $get_detail_assets = $request->detail_assets;
        if ($get_detail_assets) {
            $arr_detail_assets = explode("\n",$get_detail_assets);
            // set data detail_assets
            foreach ($arr_detail_assets as $value){
                $data_detail = array(
                    'title' => $value,
                    'type' => 0,
                    'slug' => Str::slug(trim($value), '_'),
                    'status'=> 0,
                );
                $arr_id_detail_assets[]	= $this->add_detail_assets($data_detail);
            }
        }

        // set data asset
        $quantity = $request->quantity;
        if ($file = $request->asset_image) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/asset_image', $fileName, 'public');
            $asset_image = '/storage/' . $filePath;
        }
        $data = array(
            'assets_category_id' => $request->category_id, // mã tài sản //
            'name' => $request->asset_name, // tên tài sản  //
            'company_asset_code' => $this->generateRandomString(15), //
            'is_working' => 0, // trạng thái hoạt động
            'company_id' => Auth::user()->company->company_id, // mã công ty
            'employee_id' => 0, // nhân viên
            'company_asset_code'	=> $request->asset_code,	// mã tài sản
            'purchase_date' => $request->purchase_date, // ngày đặt //
            'invoice_number' => 1234,
            'manufacturer' => 123,
            'serial_number' => "aa1111",
            'warranty_end_date' =>date('d-m-Y'),
            'price' => (int)$request->price,
            'asset_note' => $request->asset_note,
            'asset_image' => $asset_image,
        );
        for ($i=0; $i < $quantity; $i++)
        {
            $iresult = $this->add_asset($data);
            $arr_id_assets[] = $iresult;
        }

        // save into table assets_detail_assets
        if ($get_detail_assets)
        {
            foreach ($arr_id_assets as $arr_id_assets)
            {
                foreach ($arr_id_detail_assets as $arr_id_detail_asset){
                    $data_assets_detail_assets = array(
                        'assets_id' => $arr_id_assets,
                        'detail_assets_id' => $arr_id_detail_asset,
                    );
                    $save_assets_detail_assets = $this->add_assets_detail_assets($data_assets_detail_assets);
                }
            }
        }

        if ($iresult) {
            return $this->responseSuccess(__('Thêm thành công'));
        } else {
            return $this->responseError(__("xin_error_msg"));
        }
    }

    public function add_detail_assets($data)
	{
		$query = DetailAssets::firstOrCreate($data);
		if ($query) {
			return $query->id;
		} else {
			return false;
		}
    }

    public function add_asset($data)
    {
        $query = Asset::create($data);
		if ($query) {
			return $query->assets_id;
		} else {
			return false;
		}
    }

    public function add_assets_detail_assets($data)
	{
		$query = AssetsDetailAssets::create($data);
		if ($query) {
			return $query->id;
		} else {
			return false;
		}
    }

    public function generateRandomString($length = 15) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function get_all_assets_not_working()
	{
        $query = Asset::selectRaw('company_asset_code, assets_category_id, name, count(*) as total')
                        ->where('is_working', 0)
                        ->groupBy('name')
                        ->having('total', '>', 0)
                        ->get();
		return $query;
    }

    public function getEmployees($company_id)
    {
        $query = Employee::select('user_id', 'first_name', 'last_name')->where('is_active', 1)->where('company_id', $company_id)->get();
        return response()->json($query);
    }


    public function addAuthority(Request $request)
    {
        $rules = array(
            'asset_id' => 'required',
            'company_id' => 'required',
            'employee_id' => 'required'
        );
        $messages = array(
            'required' => __('field_required'),
        );
        $request->validate($rules, $messages);

        $assets_code = $request->asset_id;
		$id = array();
		foreach ($assets_code as $value)
		{
            $fetch =  $this->get_assets_id_from_company_asset_code($value);
			foreach ($fetch as $key => $value)
			{
				$id[] = $value->assets_id;
			}

        }

        //update asset
		foreach ($id as $assets_val)
		{
			$data = array(
				'is_working' => 1,
				'company_id' => $request->company_id,
				'employee_id' => $request->employee_id,
				'asset_note' => $request->asset_note,
			);
			$result_update = $this->update_assets_record($data, $assets_val);
        }

        //asset histotry
        if($result_update == true) {
            $find_employee = Employee::find($request->employee_id);
            foreach ($id as $item)
            {
                $data = array(
                    'asset_id' => $item,
                    'employee_id' => $request->employee_id,
                    'company_id' => $request->company_id,
                    'department_id' => $find_employee->department_id,
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d'),
                );
                $result = AssetHistory::create($data);
            }
        }
        if ($result) {
            return $this->responseSuccess(__('Ủy quyền thành công'));
        } else {
            return $this->responseError(__("xin_error_msg"));
        }
    }

    public function update_assets_record($data, $id){
        $find_asset = Asset::find($id);
        $query = $find_asset->update($data);
		if($query) {
			return true;
		} else {
			return false;
		}
    }

    public function get_assets_id_from_company_asset_code($company_asset_code)
	{
        $query = Asset::select('assets_id')
                        ->where('company_asset_code', $company_asset_code)
                        ->where('is_working', 0)
                        ->take(1)->get();
		return $query;
    }

    public function getDataChart()
    {
        $asset_category = AssetsCategories::get();
        $warehouse = [];
        foreach ($asset_category as $value) {
            $warehouse['cate_name'][] = $value->category_name;
            $warehouse['asset_inventory'][] = Asset::where('assets_category_id', $value->assets_category_id)->where('is_working', 0)->count();
            $warehouse['asset_active'][] = Asset::where('assets_category_id', $value->assets_category_id)->where('is_working', 1)->count();
            $warehouse['asset_inactive'][] = Asset::where('assets_category_id', $value->assets_category_id)->where('is_working', 2)->count();
        }
        return $warehouse;
    }

    public function getDataChartJson()
    {
        $warehouse = $this->getDataChart();
        return response()->json($warehouse);
    }
}
