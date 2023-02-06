<?php

namespace src\Blog\Repositories\UsersRepositories;

use src\Blog\{User, UUID};
use \PDO;
use PDOStatement;
use src\Blog\Exceptions\UserNotFoundException;
use src\Person\Name;
use src\Blog\Interfaces\UsersRepositoryInterface;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, username, first_name, last_name)
            VALUES (:uuid, :username, :first_name, :last_name)'
        );

        $statement->execute([
            'uuid' => (string)$user->uuid(),
            'username' => $user->username(),
            'first_name' => $user->name()->first(),
            'last_name' => $user->name()->last(),
        ]);
    }

    public function delete(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM users WHERE uuid = :uuid'
        );
        $statement->execute([
            'uuid' => (string)$uuid,
        ]);
    }

    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = :uuid'
        );
        $statement->execute([
            'uuid' => (string)$uuid,
        ]);
        return $this->getUser($statement, $uuid);
    }

    private function getUser(PDOStatement $statement, string $username): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new UserNotFoundException(
                "Cannot find user: $username"
            );
        }

        return new User(
            new UUID($result['uuid']),
            new Name(
                $result['first_name'],
                $result['last_name']
            ),
            $result['username'],
        );
    }

    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' => $username,
        ]);
        return $this->getUser($statement, $username);
    }

    public function getUuidByUsername(string $username): UUID
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' => $username,
        ]);
        return $this->getUser($statement, $username)->uuid();
    }
}
