<?php

namespace src\Http\Actions\Users;

use src\Http\Actions\ActionInterface;
use src\Http\{Request, Response, SuccessfulResponse, ErrorResponse};
use src\Blog\Interfaces\PasswordAuthenticationInterface;
use src\Blog\Interfaces\AuthTokensRepositoryInterface;
use src\Http\Auth\AuthException;
use DateTimeImmutable;
use src\Blog\AuthToken;

class LogIn implements ActionInterface
{
    public function __construct(
        private PasswordAuthenticationInterface $passwordAuthentication,
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $authToken = new AuthToken(
            bin2hex(random_bytes(40)), // token
            $user->uuid(),
            (new DateTimeImmutable())->modify('+1 day')
        );
        $this->authTokensRepository->save($authToken);
        return new SuccessfulResponse([
            'token' => $authToken->token(),
        ]);
    }
}
