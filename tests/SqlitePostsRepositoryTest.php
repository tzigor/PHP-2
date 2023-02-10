<?php

namespace UnitTests;

use PDO;
use PDOStatement;
use src\Blog\{User, UUID, Post};
use src\Person\Name;
use PHPUnit\Framework\TestCase;
use src\Blog\Repositories\PostsRepository;
use src\Blog\Exceptions\PostNotFoundException;

class SqlitePostsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {
        $connectionMock = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);
        $uuid = UUID::random();
        $repository = new PostsRepository($connectionMock);
        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Cannot find post: ' . (string)$uuid);
        $repository->get($uuid);
    }

    public function testItSavesPostToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                'uuid' => '123e4567-e89b-12d3-a456-426614174000',
                'author_uuid' => '123e4567-e89b-12d3-a456-426614174000',
                'title' => 'New post',
                'text' => 'Text',
            ]);

        $user = new User(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            new Name('Igor', 'Ivanov'),
            'admin',
        );
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new PostsRepository($connectionStub);
        $repository->save(
            new Post(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                $user,
                'New post',
                'Text',
            )
        );
    }

    public function testItGetPostByUuid(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock->method('fetch')->willReturn([
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'author_uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'title' => 'New post',
            'text' => 'Text',
            'username' => 'admin',
            'first_name' => 'Igor',
            'last_name' => 'Ivanov',
        ]);
        $connectionStub->method('prepare')->willReturn($statementMock);


        $repository = new PostsRepository($connectionStub);
        $post = $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
        $this->assertSame('123e4567-e89b-12d3-a456-426614174000', (string)$post->uuid());
    }
}
