<?php

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

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

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
