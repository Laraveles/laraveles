<?php

namespace Laraveles\Http\Requests;

class UpdateRecruiterRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company' => 'required|min:3',
            'website' => 'active_url',
            'avatar' => 'image'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
