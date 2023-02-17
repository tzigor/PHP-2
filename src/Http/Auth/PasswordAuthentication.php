<?php

namespace src\Http\Auth;

use src\Http\Request;
use src\Blog\User;
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Blog\Exceptions\{HttpException, UserNotFoundException};
use src\Blog\Interfaces\PasswordAuthenticationInterface;

class PasswordAuthentication implements PasswordAuthenticationInterface
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
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        if (!$user->checkPassword($password)) {
            throw new AuthException('Wrong password');
        }
        return $user;
    }
}
