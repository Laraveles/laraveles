<?php

use Mockery as m;
use Laraveles\User;
use Laraveles\Repositories\UserRepository;
use Laraveles\Services\Auth\SocialAuthenticator;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SocialAuthenticatorTest extends TestCase
{
    use DatabaseMigrations;

    public function testAuthenticatesAnExistingUser()
    {
        list($authenticator, $socialite, $auth, $repository) = $this->createInstances();
        $user = m::mock(User::class);
        $socialite->shouldReceive('driver')
                  ->andReturn(new SocialProviderStub());
        $repository->shouldReceive('findByProvider')
                   ->andReturn($user);
        $auth->shouldReceive('login')
             ->once()
             ->with($user);

        $authenticator->authenticate('github');
    }
    
    protected function createInstances()
    {
        $socialite = m::mock('Laravel\Socialite\Contracts\Factory');
        $auth = m::mock('Illuminate\Contracts\Auth\Guard');
        $repository = m::mock(UserRepository::class);
        $authenticator = new SocialAuthenticator($socialite, $auth, $repository);

        return [$authenticator, $socialite, $auth, $repository];
    }
}

class SocialProviderStub
{
    public function user()
    {
        return new UserData;
    }
}

class UserData
{
    public $id = '1';

    public $nickname = 'foo';

    public $username = 'foo';

    public $name = 'John Doe';

    public $email = 'foo@bar.com';

    public function getNickname()
    {
        return $this->nickname;
    }

    public function getId()
    {
        return $this->id;
    }
}
