<?php
namespace App\Models;

class AssetsCategories extends CacheModel
{
    protected $table = 'assets_categories';
    protected $primaryKey = 'assets_category_id';
    protected $guarded = [];

    public function assets() {
        return $this->hasMany(Asset::class, 'assets_category_id' );
    }

}
