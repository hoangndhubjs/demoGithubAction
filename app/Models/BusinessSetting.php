<?php
namespace App\Models;

use Carbon\Carbon;

class BusinessSetting extends CacheModel
{
    protected $table = 'business_settings';

    public function getValueAttribute() {
        if ($this->type === 'int') {
            return (int)$this->attributes['value'];
        } elseif ($this->type === 'hour') {
            return $this->attributes['value'] ? Carbon::createFromFormat("H:i", $this->attributes['value']) : null;
        } elseif ($this->type === "json") {
            return @json_decode($this->attributes['value']);
        } else {
            return $this->attributes['value'];
        }
    }

    public function setValueAttribute($value) {
        if ($this->type === "json") {
            return $this->attributes['value'] = @json_encode($value);
        } else {
            return $this->attributes['value'] = $value;
        }
    }
}
