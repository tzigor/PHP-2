<?php

namespace src\Http\Actions\Posts;

use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\{CommentsRepositoryInterface, UsersRepositoryInterface, CommentLikesRepositoryInterface};
use src\Blog\{UUID, Post, Like};
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException, InvalidArgumentException, CommentNotFoundException, LikeForSamePostException};
use src\Http\Auth\IdentificationInterface;
use Psr\Log\LoggerInterface;

class CreateCommentLike implements ActionInterface
{
    public function __construct(
        private CommentLikesRepositoryInterface $commentLikesRepository,
        private CommentsRepositoryInterface $commentsRepository,
        private IdentificationInterface $identification,
        private LoggerInterface $logger,
    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $commentUuid = new UUID($request->jsonBodyField('comment_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $comment = $this->commentsRepository->get($commentUuid);
        } catch (CommentNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $user = $this->identification->user($request);

        $newLikeUuid = UUID::random();
        if ($this->commentLikesRepository->userLikedForComment($commentUuid, $user->uuid())) {
            throw new LikeForSamePostException(
                "The user already liked this comment"
            );
        }
        try {
            $like = new Like(
                $newLikeUuid,
                $comment->uuid(),
                $user->uuid(),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->commentLikesRepository->save($like);
        $this->logger->info("Like for comment created: $newLikeUuid");
        return new SuccessfulResponse([
            'uuid' => (string)$newLikeUuid,
        ]);
    }
}
