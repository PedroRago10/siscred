<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientFormRequest extends FormRequest
{
    /**
     * Determine if the client is authorized to make this request.
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
            $name_rules = 'required|unique:clients,name';
        } else {
            $name_rules = 'required|unique:clients,name,'.$this->get('id');
        }

        return [
            'name' => $name_rules
        ];
    }
}
