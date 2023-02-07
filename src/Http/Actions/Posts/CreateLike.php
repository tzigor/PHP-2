<?php

namespace src\Http\Actions\Posts;

use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\{PostsRepositoryInterface, UsersRepositoryInterface, LikesRepositoryInterface};
use src\Blog\{UUID, Post, Like};
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException, UserNotFoundException, InvalidArgumentException, PostNotFoundException, LikeForSamePostException};

class CreateLike implements ActionInterface
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository,
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
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
        if ($this->likesRepository->userLikedForPost($postUuid, new UUID($user))) {
            throw new LikeForSamePostException(
                "The user already liked this post"
            );
        }
        try {
            $like = new Like(
                $newLikeUuid,
                $post->uuid(),
                $user,
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->likesRepository->save($like);
        return new SuccessfulResponse([
            'uuid' => (string)$newLikeUuid,
        ]);
    }
}
