<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            $email_rules = 'required|email|unique:users,email';
        } else {
            $email_rules = 'required|email|unique:users,id,'.$this->get('id');
        }
        return [
            'name' => 'required|max:190',
            'email' => $email_rules,
            'is_admin' => 'required|in:0,1'
        ];
    }
}
