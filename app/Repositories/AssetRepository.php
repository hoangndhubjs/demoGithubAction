<?php
namespace App\Repositories;

use App\Models\Asset;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetImage;
class AssetRepository extends Repository
{
    public function getModel(): string
    {
        return Asset::class;
    }
    /**
     * for each user
     */
    public function getAseetUser($paginateConfig, $user_info) {
        $query = Asset::with(['employeeAsset','companyAsset','categoryAsset'])->where('employee_id', $user_info->user_id)->where('company_id', $user_info->company_id);
        $listAsset = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $listAsset;
    }
    /**
     * for admin by company_id
     */
    public function listAssets($paginateConfig, $request) {
        $user_info = Auth::user();
        $query = Asset::with(['employeeAsset','companyAsset','categoryAsset'])->where('company_id', $user_info->company_id);
        if ($request->assets_category_id){
            $query->where('assets_category_id', $request->assets_category_id);
        }
        if ($request->employee_id){
            $asset_cate = $request->employee_id;
            $query->where('employee_id', function ($find) use ($asset_cate){
                $find->select('user_id')->from('employees')->where('employee_id', $asset_cate);
            });
        }
        if ($request->asset_code){
            $query->where('company_asset_code', $request->assets_category_id);
        }
        if ($request->is_working !== null){
            $query->where('is_working', $request->is_working);
        }
        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }
        $listAsset = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $listAsset;
    }
    /**
     * Xóa nhiều ảnh
     */
    public function deleteAssetImage($asset_id){
        $delete = AssetImage::where('assets_id', $asset_id)->delete();
        return $delete;
    }
}
