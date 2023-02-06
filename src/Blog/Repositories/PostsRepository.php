<?php

namespace src\Blog\Repositories;

use src\Blog\{Post, UUID};
use \PDO;
use PDOStatement;
use src\Blog\Exceptions\PostNotFoundException;
use src\Blog\Interfaces\PostsRepositoryInterface;
use src\Blog\Repositories\UsersRepositories\SqliteUsersRepository;

class PostsRepository implements PostsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
            VALUES (:uuid, :author_uuid, :title, :text)'
        );

        $statement->execute([
            'uuid' => $post->uuid(),
            'author_uuid' => $post->author_uuid(),
            'title' => $post->title(),
            'text' => $post->text(),
        ]);
    }

    public function delete(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM posts WHERE uuid = :uuid'
        );
        $statement->execute([
            'uuid' => (string)$uuid,
        ]);
    }

    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = :uuid'
        );
        $statement->execute([
            'uuid' => (string)$uuid,
        ]);
        return $this->getPost($statement, $uuid);
    }

    private function getPost(PDOStatement $statement, string $userUuid): Post
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new PostNotFoundException(
                "Cannot find post: $userUuid"
            );
        }
        $usersRepository = new SqliteUsersRepository($this->connection);
        $user = $usersRepository->get(new UUID($result['author_uuid']));
        return new Post(
            new UUID($result['uuid']),
            $user,
            $result['title'],
            $result['text']
        );
    }
}
