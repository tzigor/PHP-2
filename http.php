<?php

use Psr\Log\LoggerInterface;
use src\Blog\Exceptions\{HttpException, AppException};
use src\Http\{Request, ErrorResponse};
use src\Http\Actions\Users\FindByUsername;
use src\Http\Actions\Posts\{
    CreatePost,
    CreateComment,
    FindByUuid,
    DeletePost,
    DeleteComment,
    CreateLike,
    CreateCommentLike,
    FindLikeByPost
};
use src\Http\Actions\Users\{CreateUser, DeleteUser};

$container = require __DIR__ . '/bootstrap.php';
$logger = $container->get(LoggerInterface::class);
$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));

try {
    $path = $request->path();
} catch (HttpException $e) {
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/posts/show' => FindByUuid::class,
        '/likes/show' => FindLikeByPost::class,
    ],
    'POST' => [
        '/posts/create' => CreatePost::class,
        '/comments/create' => CreateComment::class,
        '/users/create' => CreateUser::class,
        '/likes/create' => CreateLike::class,
        '/comments/likes/create' => CreateCommentLike::class,
    ],
    'DELETE' => [
        '/users' => DeleteUser::class,
        '/posts' => DeletePost::class,
        '/comments' => DeleteComment::class,
    ]
];

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    $logger->notice("Route not found: $method $path");
    return;
}
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    $logger->notice("Path not found: $method $path");
    return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);
try {
    $response = $action->handle($request);
} catch (AppException $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    (new ErrorResponse($e->getMessage()))->send();
}
$response->send();
