<?php

namespace src\Blog\Interfaces;

use src\Blog\Like;
use src\Blog\UUID;

interface LikesRepositoryInterface
{
    public function save(Like $like): void;
    public function getByPostUuid(UUID $postUuid): array;
    public function delete(UUID $uuid): void;
    public function userLikedForPost(UUID $postUuid, UUID $usernameUuid): bool;
}
