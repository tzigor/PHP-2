<?php

namespace src\Http\Auth;

use src\Http\Request;
use src\Blog\User;

interface IdentificationInterface
{
    public function user(Request $request): User;
}
