<?php

namespace App\Repositories;

use App\Models\AttendanceTimeRequestModel;
use App\Models\Employee;
use App\Notifications\SlackNotification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceTimeRequestRepository extends Repository
{
    public function getModel(): string
    {
        return AttendanceTimeRequestModel::class;
    }

    public function create(array $attributes)
    {
        $attendance = parent::create($attributes);
        $attendance->slackChannel('channel_public')->notify(new SlackNotification($attendance->getNotifyMessageSlack()));
        $attendance->slackChannel('channel_private')->notify(new SlackNotification($attendance->getNotifyMessageSlackPrivate()));
        $module = array('name' => 'request_ot', 'id' => $attendance->time_request_id);
        $array_user_id = (config('notifications.EMPLOYEES_RECEIVE_NOTIFICATION')) ? explode(',', config('notifications.EMPLOYEES_RECEIVE_NOTIFICATION')) : array();
        if (Auth::user()->reports_to) {
            $array_user_id[] = Auth::user()->reports_to;
        }
        $notifi = (new NotificationRepository())->add_notification($attendance->getNotifyMessageSlack(), $module, $array_user_id);
        return $attendance;
    }

    /**
     * @param $paginateConfig
     * @param null $from
     * @param null $to
     * @param null $user_id
     * @return mixed
     */
    public function getOvertimeRequestByUserId($paginateConfig, $from = null, $to = null, $user_id = null)
    {
        if (!$user_id) {
            $user_id = Auth::id();
        }
        $query = $this->model->where('employee_id', $user_id)->where('company_id', Auth::user()->company_id);

        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }
        $overtime = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $overtime;
    }

    public function validateTime($request_date, $request_clock_in, $request_clock_out, $id = null)
    {

        $results = $this->model->select('time_request_id')
            ->where('employee_id', Auth::user()->user_id)
            ->where('request_date', $request_date)
            ->where(function ($query) use ($request_clock_in, $request_clock_out) {
                $query->orWhere([
                    ['request_clock_in', '=<', $request_clock_in],
                    ['request_clock_out', '>=', $request_clock_out],
                ])
                    ->orWhereBetween('request_clock_in', [$request_clock_in, $request_clock_out])
                    ->orWhereBetween('request_clock_out', [$request_clock_in, $request_clock_out]);
            })
            ->whereIn('is_approved', [1, 2]);
        if ($id) {
            $results->where('time_request_id', '<>', $id);
        }

        $result = $results->first();
        if($result != null){
            $result = $result->time_request_id;
        }
        return $result ?? null;
    }

    public function getOvertimeRequestAdmin($paginateConfig, $request)
    {
        $query = $this->model->with(['employee'])->where('company_id', Auth::user()->company_id);

        if (isset($request->created_at) && $request->created_at != null) {
            $query->where('request_date', date('Y-m-d', strtotime($request->created_at)));
        }

        if (isset($request->status) && $request->status != null) {
            $query->where('is_approved', $request->status);
        }

        $query->orderByRaw('FIELD (is_approved, 1, 2, 0 )')->orderBy('created_at', 'DESC');//

        if ($column = $paginateConfig->getSortColumn()) {
            $query->orderBy($column, $paginateConfig->getSortDir());
        }
        $overtime = $query->paginate($paginateConfig->getPerPage(), $paginateConfig->getColumns(), 'page', $paginateConfig->getPage());

        return $overtime;
    }

    public function getTimeRequest($request = null)
    {

        $request_date = date('Y-m');

        if ($request !== null && $request->month !== null) {
            $date = Carbon::createFromFormat('m-Y', $request->month);
            $request_date = $date->format('Y-m');
        }
        $query = $this->model->where('company_id', Auth::user()->company_id);

        if ($request !== null && $request->employee_name !== null) {
            $employee = Employee::with(['company'])->where('company_id', Auth::user()->company_id)
                ->where('is_active', 1)
                ->where(function ($q) use ($request) {
                    $q->whereRaw("concat(first_name, ' ', last_name) like '%$request->employee_name%' ");
                    $q->orWhereRaw("concat(last_name, ' ', first_name) like '%$request->employee_name%' ");
                    $q->orWhere("employee_id", "like", '%' . $request->employee_name . '%');
                });
            $users = [];
            foreach ($employee->get() as $user) {
                $users[] = $user->user_id;
            }

            $query->whereIn('employee_id', $users);
        }
        $query->where('request_date_request', $request_date)->where('is_approved', 2)
            ->with(['employee']);

        return $query->get();
    }

    public function getTimeRequestMonth($request = null, $employee_id)
    {

        $request_date = date('Y-m');

        if ($request !== null && $request->month !== null) {
            $date = Carbon::createFromFormat('m-Y', $request->month);
            $request_date = $date->format('Y-m');
        }

        $employee_id = Employee::where('employee_id', $employee_id)->first();
        if ($employee_id) {
            $query = $this->model->where('request_date_request', $request_date)->where('is_approved', 2)->where('employee_id', $employee_id['user_id'])->get();

            $user_request_time = [];
            foreach ($query as $key => $item) {

                $user_request_time[$item->employee_id][] = array(
                    'id' => $item->employee_id,
                    'employee_name' => $item->employee->last_name . ' ' . $item->employee->first_name,
                    'employee_id' => $item->employee_id,
                    'date' => $item->request_date,
                    'department' => $item->employee->department->department_name,
                    'total_hour_user' => $item->total_hour_minutes,
                    'total_hour_tw_user' => $item->total_tw_hour_minutes
                );

            }

            $total_hour_count = [];
            foreach ($user_request_time as $key2 => $value) {

                $map_data = array_map(function ($data) {
                    return $data['total_hour_user'];
                }, $value);

                $map_tw_data = array_map(function ($data_tw) {
                    return $data_tw['total_hour_tw_user'];
                }, $value);

                $total_hour_count[] = array(
                    'employee_name' => $value[0]['employee_name'],
                    'employee_id' => $employee_id['employee_id'],
                    'total_hour_user' => array_sum($map_data) / 60, // tổng thời gian OT chính thức trong 1 tháng
                    'total_hour_tw_user' => array_sum($map_tw_data) / 60, // tổng thời gian OT thử việc trong 1 tháng
                );

            }

            return $total_hour_count;
        }

    }

}

