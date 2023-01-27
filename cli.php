<?php

require_once __DIR__ . '/vendor/autoload.php';

use src\Blog\Command\createUserCommand;
use src\Blog\Repositories\UsersRepositories\SqliteUsersRepository;
use src\Blog\Command\Arguments;

$connection = new PDO('sqlite:' . __DIR__ . DIRECTORY_SEPARATOR . 'blog.sqlite');

$usersRepository = new SqliteUsersRepository($connection);
// $usersRepository->save(new User(UUID::random(), new Name('Ivan', 'Nikitin'), 'user'));

$command = new createUserCommand($usersRepository);
try {
    $command->handle(Arguments::fromArgv($argv));
} catch (Exception $e) {
    echo $e->getMessage();
}
