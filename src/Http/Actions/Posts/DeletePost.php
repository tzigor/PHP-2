<?php

namespace src\Http\Actions\Posts;

use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\PostsRepositoryInterface;
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\HttpException;
use src\Blog\UUID;

class DeletePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('post');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->delete(new UUID($postUuid));
        return new SuccessfulResponse([
            'post' => (string)$postUuid,
        ]);
    }
}
