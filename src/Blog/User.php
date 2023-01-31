<?php

namespace src\Blog;

use src\Person\Name;

class User
{
    public function __construct(
        private UUID $uuid,
        private Name $name,
        private string $username,
    ) {
    }
    public function __toString(): string
    {
        return "User $this->uuid with name $this->name and login $this->username." . PHP_EOL;
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function username(): string
    {
        return $this->username;
    }
}
