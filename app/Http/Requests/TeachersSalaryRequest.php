<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeachersSalaryRequest extends FormRequest
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
            'teacher_id' => 'required',
            'teacher_name' => 'required',
            'date' => 'required',
        ];
    }
}
