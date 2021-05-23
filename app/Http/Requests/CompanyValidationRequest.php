<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyValidationRequest extends FormRequest
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
            'companyName' => 'required|max:250',
            'contactNumber' => 'required|numeric',
            'email' => 'required|email',
            'loc_lotbldg' => 'required',
            'loc_street' => 'required',
            'loc_brgy' => 'required',
            'loc_city' => 'required',
            'loc_cityjson' => 'required',
            'loc_province' => 'required',
            'loc_provincejson' => 'required',
            'loc_region' => 'required',
            'loc_regionjson' => 'required',
        ];
    }
}
