<?php

namespace Laraveles\Commands\Account;

use Laraveles\Recruiter;
use Laraveles\Services\Image;
use Laraveles\Commands\CommandHandler;
use Illuminate\Contracts\Events\Dispatcher;

class UpdateRecruiterHandler extends CommandHandler
{
    /**
     * The file manager instance.
     *
     * @var Filesystem
     */
    protected $file;

    /**
     * The image service instance.
     *
     * @var Image
     */
    protected $image;

    /**
     * UpdateRecruiterHandler constructor.
     *
     * @param Image      $image
     * @param Dispatcher $dispatcher
     */
    public function __construct(Image $image, Dispatcher $dispatcher)
    {
        $this->image = $image;

        parent::__construct($dispatcher);
    }

    /**
     * Create or edit a recruiter information.
     *
     * @param UpdateRecruiter $command
     */
    public function handle(UpdateRecruiter $command)
    {
        list($actor, $company, $website, $avatar) = array_values((array) $command);

        $attributes = compact('company', 'website');

        $recruiter = $this->getRecruiter($actor, $attributes);
        $this->setAvatar($recruiter, $avatar);

        $recruiter->save();
    }

    /**
     * Will set the recruiter avatar.
     *
     * @param $avatar
     * @param $recruiter
     */
    protected function setAvatar($recruiter, $avatar = null)
    {
        if (! is_null($avatar)) {
            // Only if there is a new avatar selected we will check for any previous
            // avatar existence. If exists, we will delete the previous avatar and
            // place the new uploaded avatar image within the public directory.
            if (! empty($recruiter->avatar)) {
                $this->clearPreviousAvatar($recruiter->avatar);
            }
            $file = $this->storeAvatar($avatar);

            $recruiter->avatar = $file;
        }
    }

    /**
     * Will destroy an avatar if exists.
     *
     * @param $avatar
     */
    protected function clearPreviousAvatar($avatar)
    {
        $this->image->make(public_path("img/recruiters/" . $avatar))
                    ->clear();
    }

    /**
     * Moves the avatar to the public directory and returns its unique name
     *
     * @param $avatar
     * @return string
     */
    protected function storeAvatar($avatar)
    {
        return $this->image->make($avatar)
                           ->store(public_path('img/recruiters'));
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
