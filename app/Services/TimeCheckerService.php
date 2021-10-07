<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TimeCheckerService
{
    /**
     * @var Carbon;
     */
    private $__date;

    /**
     * @var int
     */
    private $__companyId;

    /**
     * @var Collection
     */
    protected $_leaveInLaw;

    public function __construct(int $companyId, Carbon $date) {
        $this->__companyId =$companyId;
        $this->__date =$date;
        $this->_loadLeaveInLaw();
        dd($this->_loadLeaveInLaw());
//        $this->_loadAttendanceLogs();
//        $this->_loadOfficeShift();
//        $this->_loadLeaveApplications();
//        $this->_loadOvertimeRequests();
//        $this->_loadHolidays();
//        $this->_loadRiceOrders();
//        return $this->_summaryTimer($debug);
    }

    public static function check(int $companyId, Carbon $date)
    {
        $timer = new self($companyId, $date);
        return $timer->summary();
    }

    public function summary()
    {
        return 1;
    }

    protected function _loadLeaveInLaw()
    {
        dd(LeaveInLawService::countPrevious($this->__date));
        $this->_leaveInLaw = LeaveInLawService::countPrevious($this->__date);
    }
}
