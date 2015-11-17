<?php

namespace Laraveles\Commands;

class Command
{
    /**
     * The command was validated.
     *
     * @var
     */
    protected $valid = false;

    /**
     * Command constructor.
     *
     * @param bool $valid
     */
    public function __construct($valid = false)
    {
        $this->valid = $valid;
    }

    /**
     * Sets the command as valid.
     *
     * @return $this
     */
    public function valid()
    {
        $this->valid = true;

        return $this;
    }

    /**
     * Will check if the command has been previously validated.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }
}