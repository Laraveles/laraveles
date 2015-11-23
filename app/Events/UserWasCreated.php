<?php

namespace Laraveles\Events;

use Laraveles\User;

class UserWasCreated extends Event
{
    /**
     * The user model instance.
     *O
     * @var User
     */
    public $user;

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
