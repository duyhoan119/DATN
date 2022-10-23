<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierUpdate extends FormRequest
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
                'unique:suppliers,name,'.$this->supplier->id,
                'max:100',
                'min:1'
            ],
            'address'=> [
                'required',
                'string',
                'unique:suppliers,address,'.$this->supplier->id,
                'max:100',
                'min:1'
              ]
        ];
    }
}
