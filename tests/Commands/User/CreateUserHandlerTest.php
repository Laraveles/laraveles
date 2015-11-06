<?php

use Laraveles\User;
use Laraveles\Events\UserWasCreated;
use Laraveles\Commands\User\CreateUser;
use Laraveles\Commands\User\CreateUserHandler;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateUserHandlerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $handler;

    public function setUp()
    {
        parent::setUp();
        $this->handler = $this->app->make(CreateUserHandler::class);
    }

    public function testRegisterValidUser()
    {
        $user = $this->handleCommand(['username' => 'Foo', 'password' => 'foobarbaz', 'email' => 'foo@bar.com']);

        $this->expectsEvents(UserWasCreated::class);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testPasswordAttributeWillBeSetIfNotPresent()
    {
        $user = $this->handleCommand(['username' => 'Foo', 'email' => 'foo@bar.com']);

        $this->assertNotEmpty($user->password);
    }

    public function testRegisterUserOnlyWithProviderData()
    {
        $user = $this->handleCommand([
            'provider' => (object) [
                'username' => 'Foo',
                'email'    => 'foo@bar.com',
            ]
        ]);

        $this->assertInstanceOf(User::class, $user);
    }

    public function testProviderAttributesWillBeAutomaticallySet()
    {
        $user = $this->handleCommand([
            'provider' => (object) ['username' => 'Foo', 'email' => 'foo@bar.com']
        ]);

        $this->assertEquals('Foo', $user->username);
        $this->assertEquals('foo@bar.com', $user->email);
    }

    public function testProviderAttributesDoesNotOverrideUserAttributes()
    {
        $user = $this->handleCommand([
            'username' => 'Foo',
            'email'    => 'foo@bar.com',
            'provider' => (object) ['username' => 'Bar', 'email' => 'bar@baz.com']
        ]);

        $this->assertEquals('Foo', $user->username);
        $this->assertEquals('foo@bar.com', $user->email);
    }

    public function testRegisteredUserIsNotActiveByDefault()
    {
        $user = $this->handleCommand(['username' => 'Foo', 'email' => 'foo@bar.com']);

        $this->assertFalse($user->isActive());
    }

    public function testUserWillBeActivatedWhenEmailsMatch()
    {
        $user = $this->handleCommand([
            'username' => 'Foo',
            'email'    => 'foo@bar.com',
            'provider' => (object) ['email' => 'foo@bar.com']
        ]);

        $this->assertTrue($user->isActive());
    }

    protected function handleCommand($data)
    {
        return $this->handler->handle(new CreateUser($data));
    }
}
