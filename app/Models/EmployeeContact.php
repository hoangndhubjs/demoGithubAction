<?php

namespace App\Models;

use App\Traits\SlackNotify;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;

class EmployeeContact extends CacheModel {

    protected $table = 'employee_contacts';
    protected $primaryKey = 'contact_id';

    protected $guarded = [];

}
