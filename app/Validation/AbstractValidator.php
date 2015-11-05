<?php

namespace Laraveles\Validation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

abstract class AbstractValidator
{
    /**
     * The validation factory.
     *
     * @var ValidationFactory
     */
    protected $validator;

    /**
     * AbstractValidator constructor.
     *
     * @param ValidationFactory $validator
     */
    public function __construct(ValidationFactory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Performs validation to the given attributes.
     *
     * @param array $attributes
     */
    public function validate(array $attributes)
    {
        $instance = $this->makeValidatorInstance($attributes);

        if ( ! $instance->passes()) {
            throw new ValidationException($instance);
        }
    }

    /**
     * Prepares the validator with the validation options.
     *
     * @param array $attributes
     * @return Validator
     */
    protected function makeValidatorInstance(array $attributes)
    {
        return $this->validator->make(
            $attributes, $this->rules(), $this->messages()
        );
    }

    /**
     * The validation rules to check against.
     *
     * @return array
     */
    protected function rules()
    {
        return [];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    protected function messages()
    {
        return [];
    }
}