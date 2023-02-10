<?php

namespace src\Http\Actions\Posts;

use Psr\Log\LoggerInterface;
use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\{PostsRepositoryInterface};
use src\Blog\{UUID, Post};
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException};
use src\Http\Auth\IdentificationInterface;

// http://localhost/posts/create
// {
//     "username": "ivan",
//     "title": "title",
//     "text": "Text text"
// }

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private IdentificationInterface $identification,
        private LoggerInterface $logger,
    ) {
    }
    public function handle(Request $request): Response
    {
        $user = $this->identification->user($request);
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
