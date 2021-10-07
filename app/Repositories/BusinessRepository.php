<?php
namespace App\Repositories;

use App\Models\Business;

class BusinessRepository extends Repository
{
    public function getModel(): string
    {
        return Business::class;
    }

    public function getBusinessFromDomain($domain) {
        return $this->model->whereDomain($domain)->first();
    }

    public function getBusinessFromId($id) {
        return $this->model->find($id);
    }
}
