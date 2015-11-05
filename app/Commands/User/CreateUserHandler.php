<?php

namespace Laraveles\Commands\User;

use Laraveles\Events\UserWasCreated;

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
     * Handles the creation of a new user.
     *
     * @param CreateUser $command
     */
    public function handler(CreateUser $command)
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
        $user->save();

        event(new UserWasCreated($user));
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
        if (get_array($provider, 'email') == $user->getAttribute('email')) {
            $user->activate();
        }

        foreach ($this->providerAttributes as $key => $value) {
            if ( ! isset($user->$key)) {
                $user->$key = $provider->$value;
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
        return $password ?: str_random(20);
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