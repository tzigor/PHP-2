<?php

namespace src\Http\Auth;

use src\Http\Request;
use src\Blog\User;

interface AuthenticationInterface
{
    public function user(Request $request): User;
}
