<?php

namespace Laraveles\Http\Requests;

use Laraveles\Validation\UserValidator;

class RegisterRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validator = app()->make(UserValidator::class);

        return $this->container->call([$validator, 'rules']);
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
