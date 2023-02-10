<?php

namespace src\Http\Actions\Posts;

use src\Http\Actions\ActionInterface;
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Interfaces\PostsRepositoryInterface;
use src\Blog\Exceptions\PostNotFoundException;
use src\Blog\Exceptions\HttpException;
use src\Blog\UUID;
use Psr\Log\LoggerInterface;

class FindByUuid implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('post_uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $post = $this->postsRepository->get(new UUID($postUuid));
        } catch (PostNotFoundException $e) {
            $this->logger->warning("Post not found: $postUuid");
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'title' => $post->title(),
            'text' => $post->text(),
        ]);
    }
}
