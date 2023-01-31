<?php

namespace src\Blog\Repositories;

use src\Blog\{User, UUID};
use src\Person\Name;
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Blog\Exceptions\UserNotFoundException;

class DummyUsersRepository implements UsersRepositoryInterface
{
    public function save(User $user): void
    {
    }

    public function get(UUID $uuid): User
    {
        throw new UserNotFoundException("Not found");
    }

    public function getByUsername(string $username): User
    {
        return new User(UUID::random(), new Name("first", "last"), "user123");
    }
}
