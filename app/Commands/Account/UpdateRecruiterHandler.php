<?php

namespace Laraveles\Commands\Account;

use Laraveles\Recruiter;
use Laraveles\Commands\CommandHandler;

class UpdateRecruiterHandler extends CommandHandler
{
    /**
     * Create or edit a recruiter information.
     *
     * @param UpdateRecruiter $command
     */
    public function handle(UpdateRecruiter $command)
    {
        list($actor, $company, $website, $avatar) = array_values((array) $command);
        $attributes = compact('company', 'website', 'avatar');

        $recruiter = $this->getRecruiter($actor, $attributes);

        $recruiter->save();
    }

    /**
     * Will create or update the recruiter if exists.
     *
     * @param $actor
     * @param $attributes
     * @return Recruiter
     */
    protected function getRecruiter($actor, $attributes)
    {
        $recruiter = $actor->recruiter ?: new Recruiter;

        if (! $recruiter->exists) {
            $recruiter->user()->associate($actor);
        }
        // If the recruiter relationship does not exist in database, we'll just
        // create a new recruiter instance and link it to the current actor.
        // If exists, updating fill its attributes with the new values.
        $recruiter->fill($attributes);

        return $recruiter;
    }
}
