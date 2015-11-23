<?php

use Laraveles\User;
use Laraveles\Recruiter;
use Laraveles\Commands\Account\UpdateRecruiter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laraveles\Commands\Account\UpdateRecruiterHandler;

class UpdateRecruiterHandlerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_create_recruiter_if_not_found()
    {
        $user = factory(User::class)->create();
        $command = new UpdateRecruiter($user, 'foo', 'laraveles.com');
//        $image = new \Symfony\Component\HttpFoundation\File\UploadedFile()

        $handler = $this->app->make(UpdateRecruiterHandler::class);
        $handler->handle($command);

        $this->seeInDatabase('recruiters', [
            'user_id' => $user->id,
            'company' => 'foo',
            'website' => 'laraveles.com'
        ]);
    }

    public function test_update_a_recruiter_if_found()
    {
        $user = factory(User::class)->create();
        $user->recruiter()->save(new Recruiter([
            'company' => 'foo',
            'website' => 'laraveles.com'
        ]));

        $command = new UpdateRecruiter($user, 'bar', 'foo.com');

        $handler = $this->app->make(UpdateRecruiterHandler::class);
        $handler->handle($command);

        $this->seeInDatabase('recruiters', [
            'user_id' => $user->id,
            'company' => 'bar',
            'website' => 'foo.com'
        ]);
    }
}
