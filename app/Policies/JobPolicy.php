<?php

namespace Laraveles\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Laraveles\Job;
use Laraveles\User;

class JobPolicy
{
    use HandlesAuthorization;

    /**
     * Allow job creation if recruiter profile is complete.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->recruiter ? true : false;
    }

    /**
     * Allows modification when the job belongs to a recruiter.
     *
     * @param User $user
     * @param Job  $job
     * @return bool
     */
    public function update(User $user, Job $job)
    {
        if (! $job->recruiter || ! $user->recruiter) {
            return false;
        }

        return $job->recruiter == $user->recruiter;
    }
}
