<?php

namespace spec\Laraveles\Commands\Auth;

use Laraveles\User;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Illuminate\Contracts\Auth\Guard;
use Laraveles\Repositories\UserRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Laraveles\Commands\Auth\SocialAuthenticateUser;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SocialAuthenticateUserHandlerSpec extends ObjectBehavior
{
    public function let(
        Socialite $socialite,
        Guard $auth,
        UserRepository $repo,
        Dispatcher $dispatcher
    ) {
        $this->beConstructedWith($socialite, $auth, $repo, $dispatcher);
    }
    
    public function it_is_initializable()
    {
        $this->shouldHaveType('Laraveles\Commands\Auth\SocialAuthenticateUserHandler');
    }
    
    public function it_will_log_in_an_existing_user(Socialite $socialite, Guard $auth, UserRepository $repo, User $user)
    {
        $socialite->driver('github')
                  ->shouldBeCalled()
                  ->willReturn(new SocialProviderStub);
        $repo->findByProviderOrEmail('github', '1', 'foo@bar.com')
             ->willReturn($user);

        $auth->login($user)
             ->shouldBeCalled();

        $this->handle(new SocialAuthenticateUser('github'));
    }

    public function it_does_not_find_a_local_user_and_throws_exception(Socialite $socialite, UserRepository $repo)
    {
        $socialite->driver('github')
                  ->shouldBeCalled()
                  ->willReturn(new SocialProviderStub);

        $repo->findByProviderOrEmail('github', '1', 'foo@bar.com')
             ->shouldBeCalled()
             ->willThrow(ModelNotFoundException::class);

        $this->shouldThrow(ModelNotFoundException::class)
             ->during('handle', [new SocialAuthenticateUser('github')]);
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

    public function getEmail()
    {
        return $this->email;
    }
}
