<?php

namespace src\Http\Auth;

use src\Http\Auth\IdentificationInterface;
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Blog\{User, UUID};
use src\Http\Request;
use src\Blog\Exceptions\{HttpException, UserNotFoundException, InvalidArgumentException};

class JsonBodyUuidIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }
    public function user(Request $request): User
    {
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            return $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
    }
}
