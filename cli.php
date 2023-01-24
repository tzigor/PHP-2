<?php

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
