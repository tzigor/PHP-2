<?php

namespace src\Blog;

use src\Person\Name;

class User
{
    public function __construct(
        private UUID $uuid,
        private Name $name,
        private string $username,
        private string $hashedPassword,
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

    public function hashedPassword(): string
    {
        return $this->hashedPassword;
    }

    private static function hash(string $password, UUID $uuid): string
    {
        return hash('sha256', $uuid . $password);
    }

    public function checkPassword(string $password): bool
    {
        $b = $this->hashedPassword === self::hash($password, $this->uuid);
        return $this->hashedPassword === self::hash($password, $this->uuid);
    }

    public static function createFrom(
        string $username,
        string $password,
        Name $name
    ): self {
        $uuid = UUID::random();
        return new self(
            $uuid,
            $name,
            $username,
            self::hash($password, $uuid),
        );
    }
}
