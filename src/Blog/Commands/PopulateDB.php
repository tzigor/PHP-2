<?php

namespace GeekBrains\Blog\Commands\FakeData;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use src\Blog\UUID;
use src\Blog\Interfaces\PostsRepositoryInterface;
use src\Blog\Exceptions\PostNotFoundException;
