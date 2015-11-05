<?php

namespace Laraveles\Events;

use Laraveles\User;

class UserWasCreated extends AbstractEvent
{
    /**
     * The user model instance.
     *
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
