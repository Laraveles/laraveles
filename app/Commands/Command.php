<?php

namespace Laraveles\Commands;

use Illuminate\Events\Dispatcher;

abstract class Command
{
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * Command constructor.
     *
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Firing an event.
     *
     * @param $event
     */
    protected function fire($event)
    {
        $this->dispatcher->fire($event);
    }
}