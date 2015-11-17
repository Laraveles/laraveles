<?php

namespace Laraveles\Commands\User;

use Laraveles\Commands\Command;

class CreateUser extends Command
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