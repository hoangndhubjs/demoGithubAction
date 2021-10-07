<?php
namespace App\Models;

class Asset extends CacheModel
{
    protected $table = 'assets';
    protected $primaryKey = 'assets_id';
    protected $guarded = [];

    public function employeeAsset() {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }
    public function companyAsset() {
        return $this->belongsTo(CompanyInfo::class, 'company_id', 'company_info_id');
    }
    public function categoryAsset(){
        return $this->belongsTo(AssetsCategories::class, 'assets_category_id', 'assets_category_id');
    }
}
