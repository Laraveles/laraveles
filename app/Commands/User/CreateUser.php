<?php

namespace Laraveles\Commands\User;

class CreateUser
{
    /**
     * @var array
     */
    public $data;

    /**
     * CreateUser constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}