<?php

namespace src\Blog;

class Like
{
    public function __construct(
        private UUID $uuid,
        private UUID $postUuid,
        private UUID $usernameUuid,
    ) {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function post(): UUID
    {
        return $this->postUuid;
    }

    public function username(): UUID
    {
        return $this->usernameUuid;
    }
}
