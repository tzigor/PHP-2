<?php

namespace src\Blog\Interfaces;

use src\Blog\Comment;
use src\Blog\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
    public function delete(UUID $uuid): void;
}
