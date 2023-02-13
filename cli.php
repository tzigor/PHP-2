<?php

<<<<<<< HEAD
use Psr\Log\LoggerInterface;
use src\Blog\Commands\{createUserCommand, Arguments};
use src\Blog\Repositories\UsersRepositories\SqliteUsersRepository;
use src\Blog\Repositories\{PostsRepository, CommentsRepository};
use src\Blog\Exceptions\AppException;
use src\Blog\{Post, Comment, UUID, User};
use src\Person\Name;

$container = require __DIR__ . '/bootstrap.php';
$logger = $container->get(LoggerInterface::class);

try {
    $command = $container->get(CreateUserCommand::class);
    $command->handle(Arguments::fromArgv($argv));
} catch (AppException $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
}


// $faker = Faker\Factory::create('ru_RU');

// $connection = new PDO('sqlite:' . __DIR__ . DIRECTORY_SEPARATOR . 'blog.sqlite');

// $usersRepository = new SqliteUsersRepository($connection);
// $usersRepository->delete(new UUID('aa0561a1-ed72-4833-bea4-04b4b1123cb'));
// $newUser = new User(UUID::random(), new Name('Ivan', 'Nikitin'), 'user1');
// $usersRepository->save($newUser);

// $command = new createUserCommand($usersRepository);
// try {
//     $command->handle(Arguments::fromArgv($argv));
// } catch (Exception $e) {
//     echo $e->getMessage();
// }

// $newPost = new Post(
//     UUID::random(),
//     $newUser,
//     'New post',
//     $faker->realText(rand(50, 100)),
// );

// $postsRepository = new PostsRepository($connection);
// $postsRepository->save($newPost);

// try {
//     echo $postsRepository->get(new UUID('dc2589f6-3369-4d3e-9c1e-b4593623e382'));
// } catch (Exception $e) {
//     echo $e->getMessage();
// }

// $commentsRepository = new CommentsRepository($connection);
// $newComment = new Comment(
//     UUID::random(),
//     $newPost,
//     $faker->realText(rand(50, 100)),
// );
// $commentsRepository->save($newComment);

// try {
//     echo $commentsRepository->get(new UUID('375c045f-dc65-403e-92c5-96b7cca5a3e9'));
// } catch (Exception $e) {
//     echo $e->getMessage();
// }
=======
// require_once __DIR__ . '/vendor/autoload.php';

use src\Blog\Article_Post;
use src\Blog\Comment;
use src\User_name\Person_Name;

spl_autoload_register(function ($class) {
    $fileName = str_replace('_', '\\', basename($class));
    $file = substr($class, 0, -strlen($fileName)) . $fileName;
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});


$person = new Person_Name(
    1,
    'Igor',
    'Ivanov'
);

$post = new Article_Post(
    1,
    1,
    'Igor Ivanov',
    'Post 1',
    'My first post'
);

print_r($post);
>>>>>>> main
