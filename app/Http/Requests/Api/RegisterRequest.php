<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
        $roles = [
            'name' => 'required',
        ];

        if ($this->routeIs('api.account.update'))
        {
            $roles['email'] = "required|email|unique:users,email,{$this->user()->id}";
        }

        if ($this->routeIs('api.register'))
        {
            $roles['email'] = "required|email|unique:users,email";
            $roles['password'] = 'required|min:6';
        }
        return  $roles;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = [];
        foreach ($validator->errors()->messages() as $input=>$error)
        {
            $errors[]=['name'=>$input,'reason'=>$error];
        }
        $result = [
            'type' => $this->url(),
            'title' => "Your request parameters didn't validate.",
            'invalid-params' => $errors,
            'messages' => $validator->errors()->all()
        ];

        throw new HttpResponseException(response()->json($result , 422));
    }

}
