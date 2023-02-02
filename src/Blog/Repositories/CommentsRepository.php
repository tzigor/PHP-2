<?php

namespace src\Blog\Repositories;

use src\Blog\{Post, UUID, Comment};
use \PDO;
use PDOStatement;
use src\Blog\Exceptions\CommentNotFoundException;
use src\Blog\Interfaces\CommentsRepositoryInterface;
use src\Blog\Repositories\PostsRepository;

class CommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
            VALUES (:uuid, :post_uuid, :author_uuid, :text)'
        );

        $statement->execute([
            'uuid' => $comment->uuid(),
            'post_uuid' => (string)$comment->post_uuid(),
            'author_uuid' => (string)$comment->author_uuid(),
            'text' => $comment->text(),
        ]);
    }

    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = :uuid'
        );
        $statement->execute([
            'uuid' => (string)$uuid,
        ]);
        return $this->getComment($statement, $uuid);
    }

    public function delete(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM comments WHERE uuid = :uuid'
        );
        $statement->execute([
            'uuid' => (string)$uuid,
        ]);
    }

    private function getComment(PDOStatement $statement, string $username): Comment
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new CommentNotFoundException(
                "Cannot find comment: $username"
            );
        }

        $postRepository = new PostsRepository($this->connection);
        $post = $postRepository->get(new UUID($result['post_uuid']));

        return new Comment(
            new UUID($result['uuid']),
            $post,
            $result['text'],
        );
    }
}
