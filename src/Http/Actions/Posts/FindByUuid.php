<?php

namespace src\Http\Actions\Posts;

use src\Http\Actions\ActionInterface;
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Interfaces\PostsRepositoryInterface;
use src\Blog\Exceptions\PostNotFoundException;
use src\Blog\Exceptions\HttpException;
use src\Blog\UUID;

class FindByUuid implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
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
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'title' => $post->title(),
            'text' => $post->text(),
        ]);
    }
}
