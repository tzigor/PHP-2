<?php

namespace src\Blog\Repositories\UsersRepositories;

use src\Blog\{User, UUID};
use src\Blog\Exceptions\UserNotFoundException;
use src\Blog\Interfaces\UsersRepositoryInterface;

class InMemoryUsersRepository implements UsersRepositoryInterface
{
    private array $users = [];
    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function get(UUID $uuid): User
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $uuid) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $uuid");
    }

    public function getByUsername(string $username): User
    {
        foreach ($this->users as $user) {
            if ($user->username() === $username) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $username");
    }

    public function delete(UUID $uuid): void
    {
    }

    public function getUuidByUsername(string $username): UUID
    {
        return new UUID('');
    }
}
