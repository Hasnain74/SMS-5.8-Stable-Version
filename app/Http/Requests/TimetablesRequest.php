<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimetablesRequest extends FormRequest
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
            'class_id'=>'required',
            'period'=>'required',
            'monday'=>'required',
            'tuesday'=>'required',
            'wednesday'=>'required',
            'thursday'=>'required',
            'friday'=>'required',
            'saturday'=>'required',
        ];
    }
}
