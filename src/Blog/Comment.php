<?php

namespace src\Blog;

use src\Blog\{User, Post};

class Comment
{
    public function __construct(
        private int $id,
        private User $user,
        private Post $post,
        private string $text
    ) {
    }

    public function __toString()
    {
        return $this->user . " wrote comment " . $this->text . PHP_EOL;
    }
}
