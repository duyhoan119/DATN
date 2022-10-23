<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest; 
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'unique:products',
                'max:100',
                'min:2'
              ],
            // 'price' => [
            //     'required',
            //     'float',
            //     'max:10',
            //     'min:3'
            // ], 
            // 'quantity' => [
            //     'required',
            //     'integer',
            //     'max:5',
            //     'min:1'
            // ],  

        ];
    }   
    public function messages()
    {
        return [
            'name.required'=>'Tên sản phẩm không được bỏ trống',
            'name.unique'=>'Tên sản phẩm đã tồn tại',
            'name.max' => 'Tên sản phẩm không quá 100 kí tự',
            'name.min' => 'Tên sản phẩm tối thiểu 2 kí tự',

            // 'price.required'=>'giá sản phẩm không được bỏ trống', 
            // 'price.max' => 'giá sản phẩm không quá 10 kí tự',
            // 'price.min' => 'giá sản phẩm tối thiểu 3 kí tự',

            // 'quantity.required'=>'số lượng sản phẩm không được bỏ trống', 
            // 'quantity.max' => 'số lượng sản phẩm không quá 5 kí tự',
            // 'quantity.min' => 'số lượng sản phẩm tối thiểu 1 kí tự',
        ];
    }  
protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
