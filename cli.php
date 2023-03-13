<?php

use src\Blog\Commands\createUserCommand;
use src\Blog\Commands\{DeletePost, UpdateUser, PopulateDB};
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/bootstrap.php';
// Создаём объект приложения
$application = new Application();
// Перечисляем классы команд
$commandsClasses = [
    createUserCommand::class,
    DeletePost::class,
    UpdateUser::class,
    PopulateDB::class,
];
foreach ($commandsClasses as $commandClass) {
    // Посредством контейнера
    // создаём объект команды
    $command = $container->get($commandClass);
    // Добавляем команду к приложению
    $application->add($command);
}
// Запускаем приложение
$application->run();
