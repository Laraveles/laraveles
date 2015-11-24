<?php

namespace Laraveles\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Laraveles\Job;
use Laraveles\User;

class JobPolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

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
     * Show only if job is approved.
     *
     * @param User $user
     * @param Job  $job
     * @return mixed
     */
    public function show(User $user, Job $job)
    {
        return $job->isApproved();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function moderate(User $user)
    {
        return $user->isAdmin();
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
        return ($job->recruiter == $user->recruiter) && ($job->listing);
    }
}
