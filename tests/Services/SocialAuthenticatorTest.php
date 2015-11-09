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

    public function testDrivesToUserExistsHandlerWhenUserExists()
    {
        list($authenticator, $socialite, $user, $repository, $handler) = $this->createInstances();
        $socialite->shouldReceive('driver')
                  ->andReturn(new SocialProviderStub);
        $repository->shouldReceive('findByProvider')
                   ->andReturn($user);
        $handler->shouldReceive('userExists')
                ->once();

        $authenticator->authenticate('github');
    }
    
    public function testDrivesToUserDoNotExistsWhenUserDoNotExist()
    {
        list($authenticator, $socialite, , $repository, $handler) = $this->createInstances();
        $socialite->shouldReceive('driver')
                  ->andReturn(new SocialProviderStub);
        $repository->shouldReceive('findByProvider')
                   ->andReturn(null);
        $handler->shouldReceive('userDoesNotExist')
                ->once();

        $authenticator->authenticate('github');
    }
    
    protected function createInstances()
    {
        $socialite = m::mock('Laravel\Socialite\Contracts\Factory');
        $user = m::mock(User::class);
        $repository = m::mock(UserRepository::class);
        $handler = m::mock(HandlesSocialAuthentication::class);
        $authenticator = new SocialAuthenticator($socialite, $repository, $handler);

        return [$authenticator, $socialite, $user, $repository, $handler];
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
