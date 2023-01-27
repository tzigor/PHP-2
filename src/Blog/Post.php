<?php

namespace src\Blog;

class Post
{
    public function __construct(
        private UUID $uuid,
        private UUID $author_uuid,
        private string $title,
        private string $text,
    ) {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function author_uuid(): UUID
    {
        return $this->author_uuid;
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
        return "Post: $this->title -> $this->text." . PHP_EOL;
    }
}
