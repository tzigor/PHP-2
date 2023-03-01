<?php

namespace src\Http\Actions\Users;

use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\{UsersRepositoryInterface};
use src\Blog\{UUID, User};
use src\Person\Name;
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException};
use Psr\Log\LoggerInterface;

// http://localhost/users/create
// {
// "first_name": "Vovan",
// "last_name": "Litvinenka",
// "username": "vovan",
// "password": "123"
// }

class CreateUser implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger,
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
            $user = User::createFrom(
                $request->jsonBodyField('username'),
                $request->jsonBodyField('password'),
                $name,
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->usersRepository->save($user);
        $this->logger->info("User created: $newUserUuid");
        return new SuccessfulResponse([
            'uuid' => (string)$newUserUuid,
        ]);
    }
}
