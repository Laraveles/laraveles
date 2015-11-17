<?php

use Laraveles\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivateControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_I_can_activate_an_inactive_user_with_its_valid_token()
    {
        $user = factory(User::class)->create([
            'username' => 'laraveles',
            'email'    => 'foo@bar.baz',
            'active'   => false
        ]);

        $this->visit(route('auth.activate', [$user->token]))
             ->seeInDatabase('users', ['username' => 'laraveles', 'active' => true, 'token' => null])
             ->seePageIs(route('home'));
    }

    public function test_I_cannot_activate_an_unexisting_token()
    {
        $this->visit(route('auth.activate', str_random(30)))
             ->seePageIs(route('auth.activate.request'));
    }
    
}