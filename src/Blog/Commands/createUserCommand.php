<?php

namespace src\Blog\Commands;

use Psr\Log\LoggerInterface;
use src\Blog\{User, UUID};
use src\Person\Name;
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Blog\Exceptions\CommandException;
use src\Blog\Exceptions\UserNotFoundException;

// php cli.php username=petya first_name=Petya last_name=Petrov
class createUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    ) {
    }

    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create user command started");

        $username = $arguments->get('username');
        if ($this->userExists($username)) {
            // throw new CommandException("User already exists: $username");
            $this->logger->warning("User already exists: $username");
            return;
        }
        $uuid = UUID::random();
        $this->usersRepository->save(new User(
            $uuid,
            new Name(
                $arguments->get('first_name'),
                $arguments->get('last_name')
            ),
            $username,
            $password = $arguments->get('password')
        ));

        $this->logger->info("User created: $uuid");
    }

    private function userExists(string $username): bool
    {
        try {
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}
