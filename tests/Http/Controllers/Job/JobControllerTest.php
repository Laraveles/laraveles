<?php

use Mockery as m;
use Laraveles\User;
use Laraveles\Recruiter;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JobControllerTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_I_can_see_the_jobs_listed()
    {
        $user = $this->createUser();
        $jobs = factory(\Laraveles\Job::class, 5)->make(['listing' => true])->each(function ($j) use ($user) {
            $j->recruiter()->associate($user->recruiter);
            $j->save();
        });

        $this->visit(route('job.index'));
        foreach ($jobs as $job) {
            $this->see($job->title);
        }
    }

    public function test_I_can_see_a_job_details()
    {
        $user = $this->createUser();
        $job = factory(\Laraveles\Job::class)->make(['listing' => true]);
        $job->recruiter()->associate($user->recruiter);
        $job->save();

        $this->visit(route('job.show', $job->id))
             ->seeInElement('h2', $job->title);
    }
    
    public function test_I_cant_modify_a_job_if_I_am_not_the_recruiter()
    {
        $user = $this->createUser();
        $job = factory(\Laraveles\Job::class)->make(['listing' => true]);
        $job->recruiter()->associate($user->recruiter);
        $job->save();

        $this->visit(route('job.edit', $job->id))
             ->seePageIs(route('auth.login'));

        $this->actingAs($user)
             ->visit(route('job.edit', $job->id))
             ->see('Guardar');
    }

    public function test_I_can_see_a_job_after_approved()
    {
        $user = $this->createAdminUser();
        $job = factory(\Laraveles\Job::class)->make(['title' => 'developer']);
        $job->recruiter()->associate($user->recruiter);
        $job->save();

        $this->visit(route('job.index'))
             ->dontSee('developer');

        $this->actingAs($user)
             ->visit(route('job.show', $job->id))
             ->see('Aprobar')
             ->click('Aprobar')
             ->seePageIs(route('job.index'))
             ->see('developer');
    }
    
    public function test_I_can_delete_a_job_if_I_am_admin()
    {
        $user = $this->createAdminUser();
        $job = factory(\Laraveles\Job::class)->make(['title' => 'developer', 'listing' => true]);
        $job->recruiter()->associate($user->recruiter);
        $job->save();

        $this->actingAs($user)
             ->visit(route('job.show', $job->id))
             ->see('Eliminar');
    }

    protected function createUser()
    {
        $user = factory(User::class)->create();
        $user->recruiter()->save(new Recruiter(['company' => 'Foo']));

        return $user;
    }
    
    protected function createAdminUser()
    {
        $user = m::mock($this->createUser());
        $user->shouldReceive('isAdmin')
             ->andReturn(true);

        return $user;
    }

}