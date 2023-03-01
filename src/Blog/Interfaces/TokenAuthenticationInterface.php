<?php


namespace src\Blog\Interfaces;

use src\Http\Request;
use src\Blog\AuthToken;
use src\Http\Auth\AuthenticationInterface;

interface TokenAuthenticationInterface extends AuthenticationInterface
{
    public function getToken(Request $request): AuthToken;
}
