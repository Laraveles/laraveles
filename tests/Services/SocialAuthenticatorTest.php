<?php

use Mockery as m;
use Laraveles\User;
use Laraveles\Repositories\UserRepository;
use Laraveles\Services\Auth\SocialAuthenticator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laraveles\Services\Auth\HandlesSocialAuthentication;

class SocialAuthenticatorTest extends TestCase
{
    use DatabaseMigrations;

    public function testAuthenticatesAnExistingUser()
    {
        list($authenticator, $socialite, $auth, $repository, $handler) = $this->createInstances();
        $user = m::mock(User::class);
        $socialite->shouldReceive('driver')
                  ->andReturn(new SocialProviderStub());
        $repository->shouldReceive('findByProvider')
                   ->andReturn($user);
        $handler->shouldReceive('userExists')
                ->once();
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
        $handler = m::mock(HandlesSocialAuthentication::class);
        $authenticator = new SocialAuthenticator($socialite, $auth, $repository, $handler);

        return [$authenticator, $socialite, $auth, $repository, $handler];
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
