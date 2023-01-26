<?php

require_once __DIR__ . '/vendor/autoload.php';

use src\Blog\Repositories\UsersRepositories\SqliteUsersRepository;
use src\Blog\User;
use src\Person\Name;

$connection = new PDO('sqlite:' . __DIR__ . DIRECTORY_SEPARATOR . 'blog.sqlite');

$usersRepository = new SqliteUsersRepository($connection);
$usersRepository->save(new User(1, new Name('Ivan', 'Nikitin'), 'admin'));
$usersRepository->save(new User(2, new Name('Anna', 'Petrova'), 'igor'));
