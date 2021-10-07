<?php
namespace App\Repositories;

use App\Models\Announcement;
use App\Models\Company;
use App\Models\Department;
use App\Models\OfficeLocation;

class AnnouncementRepository extends Repository
{
    public function getModel(): string
    {
        return Announcement::class;
    }

    /**
     * @param $company_id
     * @param $department_id
     * @param null $name
     * @return mixed
     */

    public function listAnnouncement($paginateConfig, $request){
        $query = Announcement::with(['companyAsset', 'departmentAsset', 'locationAsset']);
        $query_string = $request->query_string;
        if ($query_string != ''){
            $announcements = Announcement::whereRaw("concat(title) like '%$query_string%' ")->get();
            $announcement_id = [];
            foreach ($announcements as $id){
                $announcement_id[] = $id->announcement_id;
            }
            $departments = Department::whereRaw("concat(department_name) like '%$query_string%' ")->get();
            $department_id = [];
            foreach ($departments as $id){
                $department_id[] = $id->department_id;
            }
            $companies = Company::whereRaw("concat(name) like '%$query_string%' ")->get();
            $company_id = [];
            foreach ($companies as $id){
                $company_id[] = $id->company_id;
            }
            $branchs = OfficeLocation::whereRaw("concat(location_name) like '%$query_string%' ")->get();
            $location_id = [];
            foreach ($branchs as $id){
                $location_id[] = $id->location_id;
            }
            if($company_id){
                $query->whereIn("company_id", $company_id);
            }else if($announcement_id){
                $query->whereIn("announcement_id", $announcement_id);
            }else if($department_id){
                $query->whereIn("department_id", $department_id);
            }else if($location_id){
                $query->whereIn("location_id", $location_id);
            }else{$query->whereIn("announcement_id", $announcement_id);}
        }
        $listAnnouncement = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());
        return $listAnnouncement;
    }
}
