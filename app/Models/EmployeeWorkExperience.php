<?php

namespace App\Models;

use App\Traits\SlackNotify;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;

class EmployeeWorkExperience extends CacheModel {

    protected $table = 'employee_work_experience';
    protected $primaryKey = 'work_experience_id';

    protected $guarded = [];

}
