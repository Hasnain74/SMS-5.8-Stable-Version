<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required',
            'join_date' => 'required',
            'gender' => 'required',
            'teacher_qualification' => 'required',
            'nic_no' => 'required',
            // 'photo_id' => 'required',
            'phone_no' => 'required',
            'full_address' => 'required',
            'salary' => 'required'
        ];
    }
}
