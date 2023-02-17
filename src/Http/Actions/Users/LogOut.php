<?php

namespace src\Http\Actions\Users;

use src\Http\Actions\ActionInterface;
use src\Http\{Request, Response, SuccessfulResponse};
use src\Blog\Interfaces\AuthTokensRepositoryInterface;
use src\Blog\Interfaces\TokenAuthenticationInterface;
use DateTimeImmutable;

class LogOut implements ActionInterface
{
    public function __construct(
        private AuthTokensRepositoryInterface $authTokensRepository,
        private TokenAuthenticationInterface $authentication,
    ) {
    }
    public function handle(Request $request): Response
    {
        $authToken = $this->authentication->getToken($request);

        $this->authTokensRepository->updateDate($authToken->token(), new DateTimeImmutable());
        return new SuccessfulResponse([
            'token' => $authToken->token(),
        ]);
    }
}
