<?php

$connection = new PDO('sqlite:' . __DIR__ . DIRECTORY_SEPARATOR . 'blog.sqlite');
$connection->exec(
    "INSERT INTO users (first_name, last_name) VALUES ('Ivan', 'Nikitin')"
);
