<?php

namespace src\Http\Actions\Posts;

use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\{CommentsRepositoryInterface, UsersRepositoryInterface, CommentLikesRepositoryInterface};
use src\Blog\{UUID, Post, Like};
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException, UserNotFoundException, InvalidArgumentException, CommentNotFoundException, LikeForSamePostException};

class CreateCommentLike implements ActionInterface
{
    public function __construct(
        private CommentLikesRepositoryInterface $commentLikesRepository,
        private CommentsRepositoryInterface $commentsRepository,
        private UsersRepositoryInterface $usersRepository,
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
        try {
            $authorUuid = $request->jsonBodyField('username');
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $user = $this->usersRepository->getUuidByUsername($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $newLikeUuid = UUID::random();
        if ($this->commentLikesRepository->userLikedForComment($commentUuid, new UUID($user))) {
            throw new LikeForSamePostException(
                "The user already liked this comment"
            );
        }
        try {
            $like = new Like(
                $newLikeUuid,
                $comment->uuid(),
                $user,
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->commentLikesRepository->save($like);
        return new SuccessfulResponse([
            'uuid' => (string)$newLikeUuid,
        ]);
    }
}
