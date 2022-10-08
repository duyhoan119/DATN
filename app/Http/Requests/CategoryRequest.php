<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
          'name'=> [
            'required',
            'string',
            'unique:categories',
            'max:100',
            'min:2'
          ]
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'Tên loại không được bỏ trống',
            'name.unique'=>'Tên loại đã tồn tại vui lòng chọn tên khác',
            'name.max' => 'Tên chỉ đc nhập k quá 100 kí tự',
            'name.min' => 'Tên phải có ít nhất 2 kí tự'
        ];
    }
}
