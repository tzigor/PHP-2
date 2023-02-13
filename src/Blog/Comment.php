<?php

namespace src\Blog;

<<<<<<< HEAD
class Comment
{
    public function __construct(
        private UUID $uuid,
        private Post $post,
        private string $text,
        private User $user
    ) {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function post_uuid(): UUID
    {
        return $this->post->uuid();
    }

    public function author_uuid(): UUID
    {
        return $this->user->uuid();
    }

    public function __toString()
    {
        return $this->text . PHP_EOL;
    }

    public function text(): String
    {
        return $this->text;
    }
=======

class Comment
{
    public function __construct(
        private int $id,
        private int $authorId,
        private int $blogId,
        private string $text
    ) {
    }
>>>>>>> main
}
