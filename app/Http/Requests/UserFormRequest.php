<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
            'password' => 'nullable|min:8|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '*O nome é obrigatório',
            'name.max' => '*O tamanho máximo do nome é 190 caracteres',
            'email.required' => '*O email é obrigatório',
            'password.min' => '*O tamanho mínimo é 8 caracteres',
            'password.confirmed' => '*As senha informadas não coincidem',
        ];
    }
