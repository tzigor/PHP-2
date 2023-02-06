<?php

namespace src\Http\Actions\Users;

use src\Http\Actions\ActionInterface;
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Exceptions\{HttpException, UserNotFoundException};

class DeleteUser implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $username = $request->query('username');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = $this->usersRepository->getUuidByUsername($username);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->usersRepository->delete($user);
        return new SuccessfulResponse([
            'username' => (string)$user,
        ]);
    }
}
