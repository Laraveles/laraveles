<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_I_can_register_a_valid_user()
    {
        $this->withoutEvents()
             ->visitRegisterPage()
             ->see('Regístrate')
             ->typeValidUser()
             ->press('Regístrate')
             ->seePageIsHome()
             ->seeInDatabase('users', ['username' => 'laraveles', 'email' => 'foo@bar.baz']);
    }

    public function test_I_cannot_register_an_invalid_user()
    {
        $this->withoutEvents()
             ->visitRegisterPage()
             ->see('Regístrate')
             ->type('foo@bar.baz', 'email')
             ->press('Regístrate')
             ->seePageIs(route('auth.register.index'))
             ->see('obligatorio')
             ->dontSeeInDatabase('users', ['email' => 'foo@bar.baz']);
    }
    
    protected function typeValidUser()
    {
        return $this->type('foo@bar.baz', 'email')
                    ->type('laraveles', 'username')
                    ->type('123123123', 'password')
                    ->type('123123123', 'password_confirmation');
    }

    protected function seePageIsHome()
    {
        return $this->seePageIs(route('home'));
    }

    protected function visitRegisterPage()
    {
        return $this->visit(route('auth.register.index'));
    }
}