<?php

namespace src\Blog;

class Post
{
    public function __construct(
        private UUID $uuid,
        private User $user,
        private string $title,
        private string $text,
    ) {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function author_uuid(): UUID
    {
        return $this->user->uuid();
    }

    public function text(): String
    {
        return $this->text;
    }

    public function title(): String
    {
        return $this->title;
    }

    public function __toString(): string
    {
        return "From " . $this->user->name()->first() . " Post: $this->title -> $this->text." . PHP_EOL;
    }
}
