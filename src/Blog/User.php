<?php

namespace src\Blog;

use src\Person\Name;

class User
{
    public function __construct(
        private int $id,
        private Name $username,
        private string $login,
    ) {
    }
    public function __toString(): string
    {
        return "User $this->id with name $this->username and login $this->login." . PHP_EOL;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->username;
    }
}
