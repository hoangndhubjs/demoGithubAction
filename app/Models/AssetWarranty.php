<?php
namespace App\Models;

class AssetWarranty extends CacheModel
{
    protected $table = 'warranty_history_asset';
    protected $primaryKey = 'warranty_id';
    protected $guarded = [];

}
