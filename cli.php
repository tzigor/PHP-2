<?php

require_once __DIR__ . '/vendor/autoload.php';

use src\Blog\Command\{createUserCommand, Arguments};
use src\Blog\Repositories\UsersRepositories\SqliteUsersRepository;
use src\Blog\Repositories\{PostsRepository, CommentsRepository};
use src\Blog\{Post, Comment, UUID};

$faker = Faker\Factory::create('ru_RU');

$connection = new PDO('sqlite:' . __DIR__ . DIRECTORY_SEPARATOR . 'blog.sqlite');

$usersRepository = new SqliteUsersRepository($connection);
// $usersRepository->save(new User(UUID::random(), new Name('Ivan', 'Nikitin'), 'user'));

// $command = new createUserCommand($usersRepository);
// try {
//     $command->handle(Arguments::fromArgv($argv));
// } catch (Exception $e) {
//     echo $e->getMessage();
// }

$postsRepository = new PostsRepository($connection);
// $postsRepository->save(
//     new Post(
//         UUID::random(),
//         new UUID('8375f035-2d10-4f35-bc7b-5c5e578c7ac2'),
//         'New post',
//         $faker->realText(rand(50, 100)),
//     )
// );

try {
    echo $postsRepository->get(new UUID('235c0e61-0aee-4b07-873e-7918b7e00416'));
} catch (Exception $e) {
    echo $e->getMessage();
}

$commentsRepository = new CommentsRepository($connection);
// $commentsRepository->save(
//     new Comment(
//         UUID::random(),
//         new UUID('235c0e61-0aee-4b07-873e-7918b7e00416'),
//         new UUID('8375f035-2d10-4f35-bc7b-5c5e578c7ac2'),
//         $faker->realText(rand(50, 100)),
//     )
// );

try {
    echo $commentsRepository->get(new UUID('88050a21-501b-42eb-9c16-024e39f5c1da'));
} catch (Exception $e) {
    echo $e->getMessage();
}
