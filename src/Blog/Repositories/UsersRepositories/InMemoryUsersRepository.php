<?php

namespace src\Blog\Repositories\UsersRepositories;

use src\Blog\User;
use src\Blog\Exceptions\UserNotFoundException;

class InMemoryUsersRepository
{
    private array $users = [];
    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function get(int $id): User
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $id");
    }
}
