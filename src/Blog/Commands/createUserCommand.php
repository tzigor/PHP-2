<?php

namespace src\Blog\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use src\Blog\{User, UUID};
use src\Person\Name;
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Blog\Exceptions\UserNotFoundException;

//  php cli.php users:create Ivan Nikitin user1234567 some_password
class createUserCommand extends Command
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // Указываем имя команды;
            // мы будем запускать команду,
            // используя это имя
            ->setName('users:create')
            // Описание команды
            ->setDescription('Creates new user')
            // Перечисляем аргументы команды
            ->addArgument(
                // Имя аргумента;
                // его значение будет доступно
                // по этому имени
                'first_name',
                // Указание того,
                // что аргумент обязательный
                InputArgument::REQUIRED,
                // Описание аргумента
                'First name'
            )
            // Описываем остальные аргументы
            ->addArgument('last_name', InputArgument::REQUIRED, 'Last name')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        // Для вывода сообщения вместо логгера
        // используем объект типа OutputInterface
        $output->writeln('Create user command started');
        // Вместо использования нашего класса Arguments
        // получаем аргументы из объекта типа InputInterface
        $username = $input->getArgument('username');
        if ($this->userExists($username)) {
            // Используем OutputInterface вместо логгера
            $output->writeln("User already exists: $username");
            // Завершаем команду с ошибкой
            return Command::FAILURE;
        }
        // Перенесли из класса CreateUserCommand
        // Вместо Arguments используем InputInterface
        $user = User::createFrom(
            $username,
            $input->getArgument('password'),
            new Name(
                $input->getArgument('first_name'),
                $input->getArgument('last_name')
            )
        );
        //
        $this->usersRepository->save($user);
        // Используем OutputInterface вместо логгера
        $output->writeln('User created: ' . $user->uuid());
        // Возвращаем код успешного завершения
        return Command::SUCCESS;
    }
    // Полностью перенесли из класса CreateUserCommand
    private function userExists(string $username): bool
    {
        try {
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }

    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create user command started");

        $username = $arguments->get('username');
        if ($this->userExists($username)) {
            // throw new CommandException("User already exists: $username");
            $this->logger->warning("User already exists: $username");
            return;
        }
        $uuid = UUID::random();
        $this->usersRepository->save(new User(
            $uuid,
            new Name(
                $arguments->get('first_name'),
                $arguments->get('last_name')
            ),
            $username,
            $password = $arguments->get('password')
        ));

        $this->logger->info("User created: $uuid");
    }
}
