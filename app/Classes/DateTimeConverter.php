<?php
namespace App\Classes;

use Carbon\Carbon;

class DateTimeConverter
{
    const JSFORMATS = [
        'd-m-Y' => 'DD-MM-YYYY',
        'm-d-Y' => 'MM-DD-YYYY',
        'd-M-Y' => 'DD-MMM-YYYY',
        'M-d-Y' => 'MMM-DD-YYYY',
        'Y-m-d' => 'DD-MM-YYYY',
    ];

    const DBFORMAT = 'Y-m-d H:i:s';
    const DATE_FORMAT = 'Y-m-d';
    const HOUR_FORMAT = 'H:i:s';

    protected $format;

    public function __construct($format) {
        $this->format = $format;
    }

    public function getForDBFromDateTime($formatted_datetime) {
        $date = Carbon::createFromFormat($this->format.' '.self::HOUR_FORMAT, $formatted_datetime);
        return $date->format(self::DBFORMAT);
    }

    public function getForDBFromDate($formatted_datetime) {
        $date = Carbon::createFromFormat($this->format, $formatted_datetime);
        return $date->format(self::DATE_FORMAT);
    }

    public function getUserDate($db_date) {
        $date = Carbon::createFromFormat(self::DBFORMAT, $db_date);
        return $date->format($this->format);
    }

    public function getUserDateTime($db_date) {
        $date = Carbon::createFromFormat(self::DBFORMAT, $db_date);
        return $date->format($this->format.' '.self::HOUR_FORMAT);
    }

    public function isAfternoon() {
        //12:00:00.000000;
        $midDayHour = config('constants.ENDING_OF_MORNING', '12:00').':00.000000';
        $diff = Carbon::now()->floatDiffInSeconds($midDayHour);
        return !($diff < 0);
    }

    public function getJSDateFormat() {
        return self::JSFORMATS[$this->format];
    }
}
