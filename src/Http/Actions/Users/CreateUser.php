<?php

namespace src\Http\Actions\Users;

use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\{PostsRepositoryInterface, UsersRepositoryInterface};
use src\Blog\{UUID, Post, User};
use src\Person\Name;
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException};

class CreateUser implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    ) {
    }
    public function handle(Request $request): Response
    {
        $newUserUuid = UUID::random();

        try {
            $name = new Name(
                $request->jsonBodyField('first_name'),
                $request->jsonBodyField('last_name'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = new User(
                $newUserUuid,
                $name,
                $request->jsonBodyField('username'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->usersRepository->save($user);
        return new SuccessfulResponse([
            'uuid' => (string)$newUserUuid,
        ]);
    }
}
