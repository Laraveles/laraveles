<?php

namespace Laraveles\Commands\User;

use Laraveles\User;
use Laraveles\Events\UserWasCreated;
use Laraveles\Validation\UserValidator;

class CreateUserHandler
{
    /**
     * List of attributes to sync with the user.
     *
     * @var array
     */
    protected $providerAttributes = [
        'name',
        'username',
        'avatar',
        'github_id',
        'google_id',
        'email'
    ];

    /**
     * The validator instance.
     *
     * @var UserValidator
     */
    protected $validator;

    /**
     * CreateUserHandler constructor.
     *
     * @param UserValidator $validator
     */
    public function __construct(UserValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Handles the creation of a new user.
     *
     * @param CreateUser $command
     * @return static
     */
    public function handle(CreateUser $command)
    {
        list($username, $email, $password, $provider) = $this->extractBasicData($command->data);

        // When registering using a social provider, no password will be required
        // at all. Is for this case that we will generate a random password to
        // the user. The user will be able to modify the password later on.
        $password = $this->getPassword($password);

        // Registering a new user and syncing its data with the social provider
        // (if any). Once done, we are ready to make the user persistent and
        // also fire the event to let others now that it has been created.
        $user = User::register($username, $password, $email);
        $this->syncWithProvider($user, $provider);

        $this->validate(array_merge($user->toArray(), compact('password')));

        $user->save();

        event(new UserWasCreated($user));

        return $user;
    }

    /**
     * Performs user validation.
     *
     * @param $attributes
     */
    protected function validate($attributes)
    {
        $this->validator->validate($attributes);
    }

    /**
     * Sync the user model with the provider data.
     *
     * @param      $user
     * @param null $provider
     */
    protected function syncWithProvider($user, $provider = null)
    {
        if (! isset($provider)) {
            return;
        }

        if (isset($provider->driver)) {
            $this->formatObject($user, $provider);
        }

        // Setting any attribute available from the provider data to the model.
        // Also checking if the email to be stored matches the one from the
        // provider. If so assume it's valid and the user will be active.
        if (isset($provider->email)) {
            if (empty($userEmail = $user->getAttribute('email')) ||
                $provider->email == $userEmail
            ) {
                $user->activate();
            }
        }

        foreach ($this->providerAttributes as $key) {
            if (! isset($user->$key) && isset($provider->$key)) {
                $user->setAttribute($key, $provider->$key);
            }
        }
    }

    /**
     * Formatting the provider user with custom attributes.
     *
     * @param $user
     * @param $provider
     */
    protected function formatObject($user, $provider)
    {
        // Setting the provider name + _id field will match the convention used
        // for storing the unique provider user identification number in the
        // users table. As an example: github_id, google_id, facebook_id.
        $field = $provider->driver . '_id';

        $user->setAttribute($field, $provider->getId());

        if (! $this->usernameExists($provider->getNickname())) {
            $user->setAttribute('username', $provider->getNickname());
        }
    }

    /**
     * Will check if the username is already taken by another user.
     *
     * @param $username
     * @return mixed
     */
    protected function usernameExists($username)
    {
        return User::where('username', $username)
                   ->count();
    }

    /**
     * Provides a password if none.
     *
     * @param $password
     *
     * @return string
     */
    protected function getPassword($password)
    {
        return bcrypt($password ?: str_random(8));
    }

    /**
     * Extracting the data from the command.
     *
     * @param $data
     *
     * @return array
     */
    protected function extractBasicData($data)
    {
        return [
            array_get($data, 'username'),
            array_get($data, 'email'),
            array_get($data, 'password'),
            array_get($data, 'provider')
        ];
    }
}
