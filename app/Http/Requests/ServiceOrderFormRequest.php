<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceOrderFormRequest extends FormRequest
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
            'code' => 'required',
            'client_id' => 'required',
            'city_id' => 'required',
            'amount' => 'required',
            'displacement' => 'required',
            'published_at' => 'required',
            'deadline' => 'required',
            'inspection' => 'required',
            'report' => 'required',
            'finished' => 'required',
        ];
    }
}
