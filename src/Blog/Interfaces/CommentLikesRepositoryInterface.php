<?php

namespace src\Blog\Interfaces;

use src\Blog\Like;
use src\Blog\UUID;

interface CommentLikesRepositoryInterface
{
    public function save(Like $like): void;
    public function getByPostUuid(UUID $postUuid): array;
    public function delete(UUID $uuid): void;
    public function userLikedForComment(UUID $commentUuid, UUID $usernameUuid): bool;
}
