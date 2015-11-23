<?php

namespace Laraveles\Commands\Account;

use Laraveles\User;
use Laraveles\Commands\Command;

class UpdateRecruiter extends Command
{
    /**
     * The actor.
     *
     * @var User
     */
    public $actor;

    /**
     * The company name.
     *
     * @var string
     */
    public $company;

    /**
     * The company website.
     *
     * @var string
     */
    public $website;

    /**
     * The company avatar.
     *
     * @var file
     */
    public $avatar;

    /**
     * UpdateRecruiter constructor.
     *
     * @param User   $actor
     * @param string $company
     * @param string $website
     * @param string $avatar
     */
    public function __construct(
        User $actor,
        $company,
        $website = null,
        $avatar = null
    ) {
        $this->actor = $actor;
        $this->company = $company;
        $this->website = $website;
        $this->avatar = $avatar;
    }
}