<?php

namespace Laraveles\Events;

use Laraveles\User;
use Laraveles\Events\Event;

class UserWasCreated extends Event
{
    /**
     * @var User
     */
    protected $user;

    /**
     * UserWasCreated constructor.
     *
     * @param User      $user   The user actuallycreated.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
