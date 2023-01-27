<?php

namespace src\Blog\Command;

use src\Blog\{User, UUID};
use src\Person\Name;
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Blog\Exceptions\CommandException;
use src\Blog\Exceptions\UserNotFoundException;

class createUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }

    public function handle(Arguments $arguments): void
    {
        $username = $arguments->get('username');
        if ($this->userExists($username)) {
            throw new CommandException("User already exists: $username");
        }
        $this->usersRepository->save(new User(
            UUID::random(),
            new Name(
                $arguments->get('first_name'),
                $arguments->get('last_name')
            ),
            $username,
        ));
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
