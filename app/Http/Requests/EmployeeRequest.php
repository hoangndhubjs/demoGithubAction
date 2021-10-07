<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
        $rules =  [
            'employee_id' => 'required|numeric|digits_between:1,5|unique:employees,employee_id,'.$this->user_id.',user_id',
            'office_shift_id' => 'required',
            'reports_to' => 'required',
            'first_name' => 'required|regex:/^[\p{L}\s-]+$/u',
            'last_name' => 'required|regex:/^[\p{L}\s-]+$/u',
            'username' => 'required|unique:employees,username,'.$this->user_id.',user_id',
            'company_id' => 'required',
            'location_id' => 'required',
            'email' => 'required|unique:employees,email,'.$this->user_id.',user_id',
            'pin_code' => 'required',
            'date_of_birth' => 'required|date:d-m-Y|before:today',
            'gender' => 'required',
            'role' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'date_of_joining' => 'required|date:d-m-Y',
            'contact_no' => 'required|digits:10',
            'address' => 'required',
            /*'salary_trail_work' => 'required|numeric',*/
            /*'end_trail_work' => 'required|date:d-m-Y',*/
            'basic_salary' => 'required|numeric',
        ];

        if (!$this->has('user_id'))
        {
            $rules += [
                'password' => 'required|same:confirm_password|min:6',
                'confirm_password' => 'required'
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            /*'address.required' => 'hihi đồ ngkoc',*/
        ];
    }

//    public function response(array $errors)
//    {
//        return new JsonResponse(['error' => $errors], 400);
//    }
}
