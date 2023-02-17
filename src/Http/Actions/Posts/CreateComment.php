<?php

namespace src\Http\Actions\Posts;

use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\{PostsRepositoryInterface, CommentsRepositoryInterface};
use src\Blog\{UUID, Comment};
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException, PostNotFoundException, InvalidArgumentException};
use src\Http\Auth\AuthenticationInterface;
use Psr\Log\LoggerInterface;

// http://localhost/comments/create
// {
// "post_uuid": "235c0e61-0aee-4b07-873e-7918b7e00416",
// "username": "ivan",
// "text": "Comment for post"
// }

class CreateComment implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private AuthenticationInterface $identification,
        private CommentsRepositoryInterface $commentsRepository,
        private LoggerInterface $logger,
    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $post = $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $user = $this->identification->user($request);

        $newCommentUuid = UUID::random();
        try {
            $comment = new Comment(
                $newCommentUuid,
                $post,
                $request->jsonBodyField('text'),
                $user
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->commentsRepository->save($comment);
        $this->logger->info("Comment created: $newCommentUuid");
        return new SuccessfulResponse([
            'uuid' => (string)$newCommentUuid,
        ]);
    }
}
