<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PlayerRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'name' => 'required',
            'nivel_id' => 'required',
            'goalkeeper' => 'required'
        ];

        if ($request->_method == 'PUT') {
            unset($rules['user_id']);
        }

        return $rules;
    }
}
