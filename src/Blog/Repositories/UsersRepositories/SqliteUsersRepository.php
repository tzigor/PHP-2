<?php

namespace src\Blog\Repositories\UsersRepositories;

use src\Blog\User;
use \PDO;

class SqliteUsersRepository
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (first_name, last_name)
            VALUES (:first_name, :last_name)'
        );

        $statement->execute([
            ':first_name' => $user->name()->first(),
            ':last_name' => $user->name()->last(),
        ]);
    }
}
