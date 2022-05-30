<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return ['phone' => 'required|min:10|numeric'
           // 'phone' => [
           //      'required',
           //      'numeric',
           //      ]
            
         ];
       
        
    }

     /**
     * Custom message for validation
     *
     * @return array
     */
    
}