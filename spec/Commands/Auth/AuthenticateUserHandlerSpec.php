<?php

namespace spec\Laraveles\Commands\Auth;

use Illuminate\Events\Dispatcher;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Illuminate\Contracts\Auth\Guard;
use Laraveles\Commands\Auth\AuthenticateUser;
use Laraveles\Exceptions\Auth\InactiveUserException;

class AuthenticateUserHandlerSpec extends ObjectBehavior
{
    public function let(Guard $auth, Dispatcher $dispatcher)
    {
        $this->beConstructedWith($auth, $dispatcher);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Laraveles\Commands\Auth\AuthenticateUserHandler');
    }
    
    public function it_accepts_username_as_username(Guard $auth)
    {
        $command = new AuthenticateUser('laraveles', 'foobarbaz', true);
        $credentials = ['username' => 'laraveles', 'password' => 'foobarbaz'];
        $auth->attempt($credentials, true)
             ->shouldBeCalled();
        $this->handle($command);
    }

    public function it_accepts_email_as_username(Guard $auth)
    {
        $command = new AuthenticateUser('foo@bar.com', 'foobarbaz', true);
        $credentials = ['email' => 'foo@bar.com', 'password' => 'foobarbaz'];
        $auth->attempt($credentials, true)
             ->shouldBeCalled();
        $this->handle($command);
    }

    public function it_throws_an_exception_if_user_is_not_activated(Guard $auth)
    {
        $command = new AuthenticateUser('foo@bar.com', 'foobarbaz', true);
        $auth->attempt(['email' => 'foo@bar.com', 'password' => 'foobarbaz'], true)
             ->shouldBeCalled()
             ->willReturn(true);
        $auth->user()
             ->shouldBeCalled()
             ->willReturn(new UserStub);
        $auth->logout()
             ->shouldBeCalled();

        $this->shouldThrow(InactiveUserException::class)
             ->during('handle', [$command]);
    }
}

class UserStub
{
    public function isActive()
    {
        return false;
    }
}
