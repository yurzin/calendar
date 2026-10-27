<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class StoreMailApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'        => 'nullable|email|max:255',
            'lastname'     => 'nullable|string|max:255',
            'name'         => 'nullable|string|max:255',
            'patronymic'   => 'nullable|string|max:255',
            'birthday'     => 'nullable|string|max:32',
            'city'         => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'position'     => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:32',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Log::warning('tilda webhook validation failed', [
            'errors' => $validator->errors()->toArray(),
        ]);

        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }
}
