<?php

use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use src\Blog\Container\DIContainer;
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Blog\Interfaces\PostsRepositoryInterface;
use src\Blog\Interfaces\CommentsRepositoryInterface;
use src\Blog\Interfaces\LikesRepositoryInterface;
use src\Blog\Interfaces\CommentLikesRepositoryInterface;
use src\Blog\Repositories\PostsRepository;
use src\Blog\Repositories\CommentsRepository;
use src\Blog\Repositories\LikesRepository;
use src\Blog\Repositories\CommentLikesRepository;
use src\Blog\Repositories\UsersRepositories\SqliteUsersRepository;
use src\Http\Auth\IdentificationInterface;
use src\Http\Auth\JsonBodyUuidIdentification;

require_once __DIR__ . '/vendor/autoload.php';
// Загружаем переменные окружения из файла .env
\Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$logger = (new Logger('blog'));
if ('yes' === $_SERVER['LOG_TO_FILES']) {
    $logger
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.log'
        ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.error.log',
            level: Logger::ERROR,
            bubble: false,
        ));
}

if ('yes' === $_SERVER['LOG_TO_CONSOLE']) {
    $logger
        ->pushHandler(
            new StreamHandler("php://stdout")
        );
}

$container->bind(
    LoggerInterface::class,
    $logger
);

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
);

$container->bind(
    PostsRepositoryInterface::class,
    PostsRepository::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);

$container->bind(
    IdentificationInterface::class,
    JsonBodyUuidIdentification::class
);

$container->bind(
    CommentsRepositoryInterface::class,
    CommentsRepository::class
);

$container->bind(
    LikesRepositoryInterface::class,
    LikesRepository::class
);

$container->bind(
    CommentLikesRepositoryInterface::class,
    CommentLikesRepository::class
);

return $container;
