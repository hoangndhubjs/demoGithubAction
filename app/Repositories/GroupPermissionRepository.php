<?php
namespace App\Repositories;

use App\Models\GroupPermission;

class GroupPermissionRepository extends Repository
{
    public function getModel(): string
    {
        return GroupPermission::class;
    }

    /**
     * get list company
     *
     * @return mixed
     */
    public function getListGroup()
    {
        $query = $this->model->select('id', 'name')
            ->pluck('name', 'id');
        return $query;
    }
}
