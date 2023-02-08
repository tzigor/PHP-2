<?php

namespace src\Blog\test\Commands;

use src\Blog\{User, UUID};
use src\Blog\Commands\Arguments;
use src\Blog\Exceptions\CommandException;
use src\Blog\Commands\CreateUserCommand;
use src\Blog\Repositories\UsersRepositories\DummyUsersRepository;
use src\Blog\Exceptions\UserNotFoundException;
use src\Blog\Interfaces\UsersRepositoryInterface;
use src\Blog\Exceptions\ArgumentsException;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {
        $command = new CreateUserCommand(new DummyUsersRepository());
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('User already exists: Ivan');
        $command->handle(new Arguments(['username' => 'Ivan']));
    }

    public function testItRequiresFirstName_(): void
    {
        $usersRepository = new class implements UsersRepositoryInterface
        {
            public function save(User $user): void
            {
            }

            public function delete(UUID $uuid): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getUuidByUsername(string $username): UUID
            {
                return UUID::random();
            }
        };
        $command = new CreateUserCommand($usersRepository);
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: first_name');
        $command->handle(new Arguments(['username' => 'Ivan']));
    }

    private function makeUsersRepository(): UsersRepositoryInterface
    {
        return new class implements UsersRepositoryInterface
        {
            public function save(User $user): void
            {
            }
            public function delete(UUID $uuid): void
            {
            }
            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getUuidByUsername(string $username): UUID
            {
                return UUID::random();
            }
        };
    }

    public function testItRequiresLastName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository());
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: last_name');
        $command->handle(new Arguments([
            'username' => 'Ivan',
            'first_name' => 'Ivan',
        ]));
    }

    public function testItRequiresFirstName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository());
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: first_name');
        $command->handle(new Arguments(['username' => 'Ivan']));
    }

    public function testItSavesUserToRepository(): void
    {
        $usersRepository = new class implements UsersRepositoryInterface
        {
            private bool $called = false;
            public function save(User $user): void
            {
                $this->called = true;
            }
            public function delete(UUID $uuid): void
            {
            }
            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getUuidByUsername(string $username): UUID
            {
                return UUID::random();
            }
            public function wasCalled(): bool
            {
                return $this->called;
            }
        };
        $command = new CreateUserCommand($usersRepository);
        $command->handle(new Arguments([
            'username' => 'Ivan',
            'first_name' => 'Ivan',
            'last_name' => 'Nikitin',
        ]));
        $this->assertTrue($usersRepository->wasCalled());
    }
}
