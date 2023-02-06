<?php

namespace src\Http\Actions;
use src\Http\Request;
use src\Http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}