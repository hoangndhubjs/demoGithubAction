<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'leave_type' => 'required',
            'leave_time_types' => 'required',
            'start_date' => 'required_if:leave_time_types,1',
            'start_date_half_day' => 'required_if:leave_time_types,2',
            'start_date_in_day' => 'required_if:leave_time_types,3',
            'end_date' => 'required_if:leave_time_types,1|nullable|after_or_equal:start_date',
            /*'xin_start_time' => 'required_if:leave_time_types,3',
            'xin_end_time' => 'required_if:leave_time_types,3|nullable|after:xin_start_time',*/
            'reason' => 'required|max:255',
            'attachment' => 'nullable|max:1024|mimes:pdf,gif,png,jpg,jpeg',
            'type_half_day' => 'required_if:leave_time_types,2',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'xin_end_time.after' => __('xin_error_start_end_time')
        ];
    }
}
