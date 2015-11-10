<?php

namespace Laraveles\Validation;

class UserValidator extends AbstractValidator
{
    /**
     * User validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'alpha_dash|unique:users|min:3|max:30',
            'password' => 'required|min:8',
            'email'    => 'required|email|unique:users'
        ];
    }
}