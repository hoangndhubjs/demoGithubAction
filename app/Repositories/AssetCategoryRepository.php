<?php
namespace App\Repositories;

use App\Models\AssetsCategories;

class AssetCategoryRepository extends Repository
{
    public function getModel(): string
    {
        return AssetsCategories::class;
    }

    public function getAssetCategory($paginateConfig) {

        $query = AssetsCategories::withCount(['assets']);

        $listImmigration = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $listImmigration;
    }
}
