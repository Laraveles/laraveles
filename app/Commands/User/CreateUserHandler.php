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
        'username',
        'avatar',
        'github_id',
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
     * @param $user
     */
    protected function syncWithProvider($user, $provider = null)
    {
        if ( ! isset($provider)) {
            return;
        }

        // W'll set any attribute that is available into the provider data. We
        // will also compare the provider email matches to the user email.
        // If so, we assume it's real and let it register as activated.
        if (isset($provider->email) &&
            $provider->email == $user->getAttribute('email')
        ) {
            $user->activate();
        }

        foreach ($this->providerAttributes as $key) {
            if ( ! isset($user->$key) && isset($provider->$key)) {
                $user->$key = $provider->$key;
            }
        }
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
        return $password ?: str_random(8);
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