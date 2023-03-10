<?php

namespace src\Blog\Repositories;

use src\Blog\{UUID, Like};
use \PDO;
use PDOStatement;
use src\Blog\Exceptions\LikeNotFoundException;
use src\Blog\Interfaces\CommentLikesRepositoryInterface;
use Exception;

use function PHPUnit\Framework\isEmpty;

class CommentLikesRepository implements CommentLikesRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comment_likes (uuid, comment_uuid, username_uuid)
            VALUES (:uuid, :comment_uuid, :username_uuid)'
        );

        try {
            $statement->execute([
                'uuid' => $like->uuid(),
                'comment_uuid' => $like->post(),
                'username_uuid' => $like->username(),
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function delete(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM comment_likes WHERE uuid = :uuid'
        );
        $statement->execute([
            'uuid' => (string)$uuid,
        ]);
    }

    public function getByPostUuid(UUID $postUuid): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comment_likes WHERE comment_uuid = :comment_uuid'
        );
        $statement->execute([
            'comment_uuid' => (string)$postUuid,
        ]);
        return $this->getLikes($statement, $postUuid);
    }

    private function getLikes(PDOStatement $statement, string $commentUuid): array
    {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new LikeNotFoundException(
                "Cannot find like for comment: $commentUuid"
            );
        }
        return $result;
    }

    public function userLikedForComment(UUID $commentUuid, UUID $usernameUuid): bool
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comment_likes WHERE comment_uuid = :comment_uuid AND username_uuid = :username_uuid'
        );
        $statement->execute([
            'comment_uuid' => (string)$commentUuid,
            'username_uuid' => (string)$usernameUuid,
        ]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false || count($result) == 0) {
            return false;
        } else {
            return true;
        }
    }
}
