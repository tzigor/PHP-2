<?php

namespace src\Blog;

class Post
{
    public function __construct(
        private int $id,
        private User $user,
        private string $text
    ) {
    }

    public function getId(): int
    {
        return $this->id;
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
