<?php
namespace App\Repositories;

use App\Models\AssetWarranty;
use Illuminate\Support\Facades\Auth;

class AssetWarrantyRepository extends Repository
{
    public function getModel(): string
    {
        return AssetWarranty::class;
    }

    public function getWarranty($id){
        return $this->model->where('asset_id', $id)->get();
    }
}
