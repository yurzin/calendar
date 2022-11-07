<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMailApiRequest extends FormRequest
{

    public $timestamps = false;

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
            'lastname' => 'nullable|string',
            'name' => 'nullable|string',
            'patronymic' => 'nullable|string',
            'birthday' => 'nullable|string',
            'email' => 'nullable|email',
            'city' => 'nullable|string',
            'organization' => 'nullable|string',
            'position' => 'nullable|string',
            'phone' => 'nullable|string',
            'token' => 'nullable|string',
        ];
    }
}
