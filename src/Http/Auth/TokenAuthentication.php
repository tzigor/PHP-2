<?php

namespace src\Http\Auth;

use DateTimeImmutable;
use src\Http\Request;
use src\Blog\{User, AuthToken};
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Blog\Exceptions\{HttpException, AuthTokenNotFoundException};
use src\Blog\Interfaces\TokenAuthenticationInterface;
use src\Blog\Interfaces\AuthTokensRepositoryInterface;

class TokenAuthentication implements TokenAuthenticationInterface
{
    private const HEADER_PREFIX = 'Bearer ';

    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private AuthTokensRepositoryInterface $tokensRepository,
    ) {
    }

    public function user(Request $request): User
    {
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new AuthException("Malformed token: [$header]");
        }

        $token = mb_substr($header, strlen(self::HEADER_PREFIX));

        try {
            $authToken = $this->tokensRepository->get($token);
        } catch (AuthTokenNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }

        $tokenExpiresOn = $authToken->expiresOn();
        $now = new DateTimeImmutable();
        $interval = $tokenExpiresOn->diff($now);

        if (!$interval->invert) {
            throw new AuthException('Token expired');
        }

        return $this->usersRepository->get($authToken->userUuid());
    }

    public function getToken(Request $request): AuthToken
    {
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new AuthException("Malformed token: [$header]");
        }

        $token = mb_substr($header, strlen(self::HEADER_PREFIX));

        try {
            $authToken = $this->tokensRepository->get($token);
        } catch (AuthTokenNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
        return $authToken;
    }
}
