<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentsRequest extends FormRequest
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
            'DOB' => 'required',
            'admission_date' => 'required',
            'students_class_id' => 'required',
            'gender' => 'required',
            // 'photo_id' => 'required',
            'fee_setup' => 'required',
            'student_address' => 'required',
            'guardian_name' => 'required',
            'guardian_gender' => 'required',
            'guardian_relation' => 'required',
            'guardian_occupation' => 'required',
            'guardian_phone_no' => 'required',
            'NIC_no' => 'required',
            'guardian_address' => 'required',
            'transport_fee' => 'required',
        ];
    }
}
