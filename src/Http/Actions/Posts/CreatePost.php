<?php

namespace src\Http\Actions\Posts;

use Psr\Log\LoggerInterface;
use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\{PostsRepositoryInterface};
use src\Blog\{UUID, Post};
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException};
use src\Http\Auth\AuthenticationInterface;

// http://localhost/posts/create
// {
// "username": "ivan",
// "password": "123",
// "title": "title",
// "text": "Text text"
// }

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private AuthenticationInterface $authentication,
        private LoggerInterface $logger,
    ) {
    }
    public function handle(Request $request): Response
    {
        $user = $this->authentication->user($request);
        $newPostUuid = UUID::random();
        try {
            $post = new Post(
                $newPostUuid,
                $user,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->postsRepository->save($post);
        $this->logger->info("Post created: $newPostUuid");
        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}
