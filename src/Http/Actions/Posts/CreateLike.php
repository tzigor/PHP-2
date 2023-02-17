<?php

namespace src\Http\Actions\Posts;

use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\{PostsRepositoryInterface, LikesRepositoryInterface};
use src\Blog\{UUID, Like};
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException, InvalidArgumentException, PostNotFoundException, LikeForSamePostException};
use src\Blog\Interfaces\TokenAuthenticationInterface;
use Psr\Log\LoggerInterface;

// http: //localhost/likes/create
// {
// "post_uuid": "235c0e61-0aee-4b07-873e-7918b7e00416",
// "username": "vovan"
// }

class CreateLike implements ActionInterface
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository,
        private PostsRepositoryInterface $postsRepository,
        private TokenAuthenticationInterface $identification,
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

        $newLikeUuid = UUID::random();
        if ($this->likesRepository->userLikedForPost($postUuid, $user->uuid())) {
            throw new LikeForSamePostException(
                "The user already liked this post"
            );
        }
        try {
            $like = new Like(
                $newLikeUuid,
                $post->uuid(),
                $user->uuid(),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->likesRepository->save($like);
        $this->logger->info("Like created: $newLikeUuid");
        return new SuccessfulResponse([
            'uuid' => (string)$newLikeUuid,
        ]);
    }
}
