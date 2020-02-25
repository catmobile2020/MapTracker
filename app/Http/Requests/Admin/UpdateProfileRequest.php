<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $data = [
            'name' => 'required|max:191',
        ];
        $data['email']='required|email|unique:users,email,'.$this->user('web')->id;
        if ($this->has('password') and $this->get('password') !== null)
        {
            $data['current_password']='required|min:6';
            $data['password']='required|confirmed|min:6';
        }
        return $data;
    }
}
