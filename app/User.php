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
    protected $fillable = ['name', 'username', 'email', 'password', 'avatar', 'github_id', 'google_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Booting the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->token = str_random(30);
        });
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
     * Activating the user.
     *
     * @return $this
     */
    public function activate()
    {
        $this->setAttribute('token', null);
        $this->setAttribute('active', true);

        return $this;
    }

    /**
     * Deactivating the user.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->setAttribute('active', false);

        return $this;
    }

    /**
     * Check if the user has been activated.
     *
     * @return mixed
     */
    public function isActive()
    {
        return $this->getAttribute('active') ?: false;
    }

    /**
     * Gives the activation token if any.
     *
     * @return mixed
     */
    public function getActivationToken()
    {
        return $this->getAttribute('token');
    }
}
