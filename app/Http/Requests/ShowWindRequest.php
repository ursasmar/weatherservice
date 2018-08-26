<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ShowWindRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'zipCode' => 'required|regex:/^[0-9]{5}$/'
        ];
    }

    public function messages(): array
    {
        return [
            'zipCode.required' => 'Zip Code is required',
            'zipCode.regex' => 'A US Zip Code is required',
        ];
    }

    public function all($keys = null): array
    {
        $data = parent::all();
        $data['zipCode'] = $this->route('zipCode');
        return $data;
    }

    protected function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
