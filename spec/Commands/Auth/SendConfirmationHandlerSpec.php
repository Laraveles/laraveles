<?php

namespace spec\Laraveles\Commands\Auth;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Laraveles\Mailer\AuthMailer;
use Laraveles\Repositories\UserRepository;
use Laraveles\Commands\Auth\SendConfirmation;

class SendConfirmationHandlerSpec extends ObjectBehavior
{
    public function let(AuthMailer $mailer, UserRepository $repository)
    {
        $this->beConstructedWith($mailer, $repository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Laraveles\Commands\Auth\SendConfirmationHandler');
    }
    
    public function it_sends_an_email_if_user_is_active(AuthMailer $mailer, UserRepository $repository)
    {
        $repository->ofToken('foo')
                   ->shouldBeCalled()
                   ->willReturn(new ActiveUserStub);

        $mailer->confirmation('foo@bar.baz', 'foo');

        $this->handle(new SendConfirmation('foo'));
    }
    
    public function it_does_not_send_email_if_user_is_active(AuthMailer $mailer, UserRepository $repository)
    {
        $repository->ofToken('foo')
                   ->shouldBeCalled()
                   ->willReturn(new ActiveUserStub);

        $mailer->confirmation()->shouldNotBeCalled();

        $this->handle(new SendConfirmation('foo'));
    }
}

class ActiveUserStub
{
    public function isActive()
    {
        return true;
    }
}

class InactiveUserStub
{
    public $email = 'foo@bar.baz';
    public function isActive()
    {
        return false;
    }
    public function getActivationToken()
    {
        return 'foo';
    }
}
