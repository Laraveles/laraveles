<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laraveles\User;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_I_can_not_access_to_login_page_if_I_am_authentified()
    {
        $user = $this->createBasicUser();

        $this->visitLoginRoute()
             ->see('Identifícate');

        $this->actingAs($user)
             ->visitLoginRoute()
             ->seePageIsHome();
    }

    public function test_I_can_login_using_username()
    {
        $this->createBasicUser();

        $this->visitLoginRoute()
             ->type('laraveles', 'username')
             ->type('foobarbaz', 'password')
             ->pressAccess()
             ->seePageIsHome();
    }

    public function test_I_can_login_using_email()
    {
        $this->createBasicUser();

        $this->visitLoginRoute()
             ->type('foo@bar.com', 'username')
             ->type('foobarbaz', 'password')
             ->pressAccess()
             ->seePageIsHome();
    }

    protected function createBasicUser()
    {
        return factory(User::class)->create([
            'username' => 'laraveles',
            'email'    => 'foo@bar.com',
            'password' => Hash::make('foobarbaz')
        ]);
    }

    protected function visitLoginRoute()
    {
        return $this->visit(route('auth.login'));
    }

    protected function seePageIsHome()
    {
        return $this->seePageIs(route('home'));
    }

    protected function pressAccess()
    {
        return $this->press('Acceder');
    }
}