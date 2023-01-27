<?php

namespace src\Blog\Interfaces;

use src\Blog\Post;
use src\Blog\UUID;

interface PostsRepositoryInterface
{
    public function save(Post $post): void;
    public function get(UUID $uuid): Post;
}
