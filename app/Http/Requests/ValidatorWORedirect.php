<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ValidatorWORedirect extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        return $validator;
    }

    public function passed() {
        return !$this->getValidatorInstance()->fails();
    }

    public function getErrors() {
        return $this->getValidatorInstance()->errors();
    }
}
