<?php

namespace spec\Laraveles\Services\Auth;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuardAuthenticatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Laraveles\Services\Auth\GuardAuthenticator');
    }
}
