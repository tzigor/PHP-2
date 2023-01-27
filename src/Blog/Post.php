<?php

namespace src\Blog;

class Post
{
    public function __construct(
        private UUID $uuid,
        private User $user,
        private string $text
    ) {
    }

    public function getId(): UUID
    {
        return $this->uuid;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getText(): String
    {
        return $this->text;
    }

    public function __toString()
    {
        return $this->user . ' wrote: ' . $this->text . PHP_EOL;
    }
}
