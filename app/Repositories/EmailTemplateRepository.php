<?php
namespace App\Repositories;

use App\Models\EmailTemplate;

class EmailTemplateRepository extends Repository
{
    public function getModel(): string
    {
        return EmailTemplate::class;
    }

    public function getEmailTemplate($paginateConfig) {

        $listImmigration = EmailTemplate::paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $listImmigration;
    }
}
