<?php

namespace src\Http\Actions\Posts;

use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\CommentsRepositoryInterface;
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException};
use src\Blog\UUID;

class DeleteComment implements ActionInterface
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository,
    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $commentUuid = $request->query('comment');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->commentsRepository->delete(new UUID($commentUuid));
        return new SuccessfulResponse([
            'comment' => (string)$commentUuid,
        ]);
    }
}
