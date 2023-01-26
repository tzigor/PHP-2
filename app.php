<?php

use src\Person\Name;
use src\Blog\{User, Post, Comment};

require_once __DIR__ . '/vendor/autoload.php';

$faker = Faker\Factory::create('ru_Ru');
$name = new Name(
    $faker->firstName('female'),
    $faker->lastName()
);
$user = new User(
    $faker->randomDigitNotNull(),
    $name,
    $faker->sentence(1)
);

$route = $argv[1] ?? null;

switch ($route) {
    case "user":
        echo $user;
        break;
    case "post":
        $post = new Post(
            $faker->randomDigitNotNull(),
            $user,
            $faker->realText(rand(50, 100))
        );
        echo $post;
        break;
    case "comment":
        $post = new Post(
            $faker->randomDigitNotNull(),
            $user,
            $faker->realText(rand(50, 100))
        );
        $comment = new Comment(
            $faker->randomDigitNotNull(),
            $user,
            $post,
            $faker->realText(rand(50, 100))
        );
        echo $comment;
        break;
    default:
        echo "Error: no parameters.";
}

// echo $faker->name() . PHP_EOL;
// echo $faker->realText(rand(100, 200)) . PHP_EOL;

// $usersRepository = new InMemoryUsersRepository();
// $usersRepository->save(new User(123, new Name('Ivan', 'Nikitin'), 'admin'));
// $usersRepository->save(new User(234, new Name('Anna', 'Petrova'), 'igor'));
// try {
//     $user = $usersRepository->get(333);
//     print $user->name();
// } catch (UserNotFoundException | Exception $e) {
//     print $e->getMessage();
// }
