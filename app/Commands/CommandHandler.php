<?php

namespace Laraveles\Commands;

use Illuminate\Contracts\Events\Dispatcher;

abstract class CommandHandler
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