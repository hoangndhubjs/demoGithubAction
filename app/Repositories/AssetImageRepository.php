<?php
namespace App\Repositories;

use App\Models\AssetImage;
use Illuminate\Support\Facades\Auth;

class AssetImageRepository extends Repository
{
    public function getModel(): string
    {
        return AssetImage::class;
    }

    public  function getImageAsset($asset_id){
       return  $this->model->where('assets_id', $asset_id)->get();
    }
    /**
     * Xóa từng ảnh
     */
    public function deleteEachAssetImage($asset_id){
        $delete = $this->model->where('asset_image_id', $asset_id)->delete();
        return $delete;
    }
}
