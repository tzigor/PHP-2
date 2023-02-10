<?php

namespace src\Http\Actions\Users;

use src\Http\Actions\ActionInterface;
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Blog\Exceptions\UserNotFoundException;
use src\Blog\Exceptions\HttpException;
use Psr\Log\LoggerInterface;

// http://localhost/users/show?username=ivan

class FindByUsername implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger,
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
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            $this->logger->warning("User not found: $username");
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'username' => $user->username(),
            'name' => $user->name()->first() . ' ' . $user->name()->last(),
        ]);
    }
}
