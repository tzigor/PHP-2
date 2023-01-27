<?php

namespace src\Blog;

use src\Blog\{User, Post};

class Comment
{
    public function __construct(
        private UUID $uuid,
        private Post $post,
        private User $user,
        private string $text
    ) {
    }

    public function __toString()
    {
        return $this->user . " wrote comment " . $this->text . PHP_EOL;
    }
}
