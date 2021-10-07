<?php

namespace App\Models;

use App\Announcement;
use App\Traits\SlackNotify;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Employee
 * @package App\Models
 *
 * @property OfficeShift $office_shift
 * @property int $wages_type
 * @property string (datetime) $end_trail_work
 * @property int $user_id
 */
class Employee extends CacheModel implements AuthenticatableContract
{
    use Authenticatable, Notifiable, SlackNotify, HasRoles;

    protected $table = 'employees';
    protected $primaryKey = 'user_id';
    protected $guarded = [];

    protected $hidden = [
        'password'
    ];

    const STATUS_ACTIVE = 1;

    const WAGES_FULL = 1;
    const WAGE_TRIAL = 2;
    const WAGES_PARTTIME = 3;
    const WAGES_INTERN = 4;

    public function getFullname() {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }

    public function getFirstCharacter() {
        return substr($this->first_name, 0, 1);
    }

    public function announcements(){
        return $this->belongsTo(Announcement::class, 'published_by', 'user_id');
    }

    public function designation() {
        return $this->belongsTo(Designation::class, 'designation_id', 'designation_id');
    }
    public function department() {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
    public function office_shift() {
        return $this->belongsTo(OfficeShift::class, 'office_shift_id', 'office_shift_id');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function isAdmin() {
        return $this->user_role_id == 1;
    }

    public function isEmployee() {
        return $this->user_role_id == 2;
    }

    public function report_to() {
        return $this->belongsTo(Employee::class, 'reports_to', 'user_id');
    }

    public function location(){
        return $this->belongsTo(OfficeLocation::class, 'location_id', 'location_id');
    }

    public function role(){
        return $this->belongsTo(Role::class, 'user_role_id', 'id');
    }

    public function staffs() {
        return $this->hasMany(Employee::class, 'reports_to', 'user_id');
    }

    /*public function isSuperAdmin() {
        foreach($this->roles as $role) {
            if ($role->role_access === 1) {
                return true;
            }
        }
        return false;
    }*/
}
