<?php

namespace src\Blog;

class Comment
{
    public function __construct(
        private UUID $uuid,
        private UUID $post_uuid,
        private UUID $author_uuid,
        private string $text
    ) {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function post_uuid(): UUID
    {
        return $this->post_uuid;
    }

    public function author_uuid(): UUID
    {
        return $this->author_uuid;
    }

    public function __toString()
    {
        return $this->text . PHP_EOL;
    }

    public function text(): String
    {
        return $this->text;
    }
}
