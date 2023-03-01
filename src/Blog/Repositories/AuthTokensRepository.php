<?php

namespace src\Blog\Repositories;

use Exception;
use PDO;
use PDOException;
use DateTimeImmutable;
use src\Blog\Interfaces\AuthTokensRepositoryInterface;
use src\Blog\Exceptions\AuthTokensRepositoryException;
use src\Blog\Exceptions\AuthTokenNotFoundException;
use src\Blog\{AuthToken, UUID};

class AuthTokensRepository implements AuthTokensRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(AuthToken $authToken): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO tokens (token, user_uuid, expires_on) 
            VALUES (:token, :user_uuid, :expires_on)
            ON CONFLICT (token) DO UPDATE SET expires_on = :expires_on'
        );

        try {
            $statement->execute([
                ':token' => $authToken->token(),
                ':user_uuid' => (string)$authToken->userUuid(),
                ':expires_on' => $authToken->expiresOn()->format("Y-m-d\\TH:i:sP"),
            ]);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    public function updateDate(string $token, DateTimeImmutable $expiresOn): void
    {
        $statement = $this->connection->prepare(
            'UPDATE tokens 
             SET expires_on = :expires_on
             WHERE token = :token'
        );
        try {
            $statement->execute([
                'expires_on' => $expiresOn->format("Y-m-d\\TH:i:sP"),
                'token' => $token,
            ]);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    public function get(string $token): AuthToken
    {
        try {
            $statement = $this->connection->prepare(
                'SELECT * FROM tokens WHERE token = ?'
            );
            $statement->execute([$token]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
        if (false === $result) {
            throw new AuthTokenNotFoundException("Cannot find token: $token");
        }
        try {
            return new AuthToken(
                $result['token'],
                new UUID($result['user_uuid']),
                new DateTimeImmutable($result['expires_on'])
            );
        } catch (Exception $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    public function getByUserUuid(UUID $uuid): AuthToken
    {
        try {
            $statement = $this->connection->prepare(
                'SELECT * FROM tokens WHERE user_uuid = ?'
            );
            $statement->execute([$uuid]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
        if (false === $result) {
            throw new AuthTokenNotFoundException("Cannot find token for user: $uuid");
        }
        try {
            return new AuthToken(
                $result['token'],
                new UUID($result['user_uuid']),
                new DateTimeImmutable($result['expires_on'])
            );
        } catch (Exception $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
