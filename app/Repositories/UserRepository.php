<?php

namespace Laraveles\Repositories;

use Laraveles\User;

class UserRepository
{
    /**
     * Finds an user by provider name and identifier or email. Ir using email only
     * active users will be given.
     *
     * @param      $provider
     * @param      $identifier
     * @param null $email
     * @return mixed
     */
    public function findByProviderOrEmail($provider, $identifier, $email = null)
    {
        $field = $provider . '_id';

        $query = User::where($field, $identifier);

        // We'll consider the email field only if it is present. It's assumed
        // that if a user has confimed its email address, it belongs to the
        // user so if the emails match, these users are the same person.
        if (! is_null($email)) {
            $query->orWhere(function ($query) use ($email) {
                $query->where('email', $email);
                $query->where('active', true);
            });
        }

        return $query->firstOrFail();
    }
}