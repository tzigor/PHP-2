<?php

namespace src\Blog\Interfaces;

use DateTimeImmutable;
use src\Blog\{AuthToken, UUID};

interface AuthTokensRepositoryInterface
{
    public function save(AuthToken $authToken): void;
    public function get(string $token): AuthToken;
    public function getByUserUuid(UUID $uuid): AuthToken;
    public function updateDate(string $token, DateTimeImmutable $expiresOn): void;
}
