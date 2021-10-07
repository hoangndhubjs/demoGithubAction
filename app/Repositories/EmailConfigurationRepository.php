<?php
namespace App\Repositories;

use App\Models\EmailConfiguration;

class EmailConfigurationRepository extends Repository
{
    public function getModel(): string
    {
        return EmailConfiguration::class;
    }

    public function getEmailConfiguration($id) {

        $email_config = EmailConfiguration::where('email_config_id', $id)->first();

        return $email_config;
    }
}
