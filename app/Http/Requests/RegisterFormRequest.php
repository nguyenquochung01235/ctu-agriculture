<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterFormRequest extends FormRequest
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
            'fullname' => 'required',
            'email'=>'required|email',
            'phone_number'=>'required|regex:/^([0][0-9\s\-\+\(\)]*)$/|min:10|max:12',
            'address'=>'required',
            'dob'=>'required',
            'password'=>'required'
        ];
    }

    public function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(response()->json([
            "statusCode" => 400,
            "message" => "Validation Error",
            "errorList" => [$validator->errors()],
            "data" => null
        ]));

    }

    public function messages()
    {
        return [
            'fullname.required' => 'Tên không được trống',
            'email.required'=> 'Email không được trống',
            'email.email'=> 'Email không đúng định dạng',
            'phone_number.required'=> 'Số điện thoại không được trống',
            'phone_number.regex'=>'Số điện thoại không đúng định dạng',
            'address.required'=>'Địa chỉ không được trống',
            'dob'=> 'Ngày sinh không được trống',
            'password'=>'Mật khẩu không được trống'
        ];
    }
}
