<?php

namespace Laraveles\Commands\Auth;

use Laraveles\Mailer\AuthMailer;
use Laraveles\Repositories\UserRepository;

class SendConfirmationHandler
{
    /**
     * The mailer instance.
     *
     * @var Mailer
     */
    protected $mailer;

    /**
     * The user repository.
     *
     * @var UserRepository
     */
    protected $user;

    /**
     * SendConfirmationHandler constructor.
     *
     * @param AuthMailer        $mailer
     * @param UserRepository    $user
     */
    public function __construct(AuthMailer $mailer, UserRepository $user)
    {
        $this->mailer = $mailer;
        $this->user = $user;
    }

    /**
     * Send the confirmation email.
     *
     * @param SendConfirmation $command
     */
    public function handle(SendConfirmation $command)
    {
        $user = $this->user->ofToken($command->token);

        // If the user is already active we'll just omit any further tasks. In
        // case it is not activated, we will proceed to send a confirmation
        // email with the token string that will be used for activation.
        if ($user->isActive()) {
            return;
        }

        $this->mailer->confirmation($user->email, $user->getActivationToken());
    }
}
