<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laraveles\Recruiter;
use Laraveles\User;

class RecruiterControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_I_can_set_my_recruiter_info()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
             ->visit(route('account.recruiter.index'))
             ->type('laraveles', 'company')
             ->type('laraveles.com', 'website')
            ->press('Guardar')
            ->seeInDatabase('recruiters', [
                'company' => 'laraveles',
                'website' => 'laraveles.com'
            ]);
    }

    public function test_I_can_update_my_recruiter_info()
    {
        $user = factory(User::class)->create();
        $user->recruiter()->save(new Recruiter(['company' => 'laraveles', 'website' => 'laraveles.com']));

        $this->actingAs($user)
             ->visit(route('account.recruiter.index'))
             ->type('foo', 'company')
             ->type('bar.com', 'website')
             ->press('Guardar')
             ->seeInDatabase('recruiters', [
                 'company' => 'foo',
                 'website' => 'bar.com'
             ]);
    }
    
    public function test_I_cant_access_if_not_logged_in()
    {
        $this->visit(route('account.recruiter.index'))
             ->seePageIs(route('auth.login'));
    }
}