<?php

namespace src\Blog\Interfaces;

use src\Blog\AuthToken;

interface AuthTokensRepositoryInterface
{
    public function save(AuthToken $authToken): void;
    public function get(string $token): AuthToken;
}
