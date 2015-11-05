<?php

namespace Laraveles;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'username', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Finds an user by its provider.
     *
     * @param $provider
     * @param $identifier
     * @return mixed
     */
    public static function findByProvider($provider, $identifier)
    {
        $field = $provider . '_id';

        return static::where($field, $identifier)->firstOrFail();
    }

    /**
     * Registering a new user but not yet persisting.
     *
     * @param $username
     * @param $password
     * @param $email
     * @return static
     */
    public static function register($username, $password, $email)
    {
        return new static(compact('username', 'password', 'email'));
    }

    /**
     * Check if the user has been activated.
     *
     * @return mixed
     */
    public function isActive()
    {
        return $this->getAttribute('active');
    }
}
