<?php

namespace src\Http\Actions\Posts;

use src\Http\Actions\ActionInterface;
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Interfaces\PostsRepositoryInterface;
use src\Blog\Interfaces\LikesRepositoryInterface;
use src\Blog\Exceptions\HttpException;
use src\Blog\UUID;

class FindLikeByPost implements ActionInterface
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository,
        private PostsRepositoryInterface $postsRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $postUuid = new UUID($request->query('post_uuid'));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $result = $this->likesRepository->getByPostUuid($postUuid);
        return new SuccessfulResponse($result);
    }
}
