<?php

namespace src\Blog\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use src\Blog\UUID;
use src\Blog\Interfaces\PostsRepositoryInterface;
use src\Blog\Exceptions\PostNotFoundException;

use Symfony\Component\Console\Question\ConfirmationQuestion;
//  php cli.php posts:delete 009f39a8-a333-407c-ba12-37cab0b5636a

class DeletePost extends Command
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('posts:delete')
            ->setDescription('Deletes a post')
            ->addArgument(
                'uuid',
                InputArgument::REQUIRED,
                'UUID of a post to delete'
            )
            ->addOption(
                'check-existence',
                'c',
                InputOption::VALUE_NONE,
                'Check if post actually exists',
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $question = new ConfirmationQuestion(
            'Delete post [y/n]? ',
            false
        );

        if (!$this->getHelper('question')
            ->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        $uuid = new UUID($input->getArgument('uuid'));
        if ($input->getOption('check-existence')) {
            try {
                $this->postsRepository->get($uuid);
            } catch (PostNotFoundException $e) {
                $output->writeln($e->getMessage());
                return Command::FAILURE;
            }
        }
        $this->postsRepository->delete($uuid);
        $output->writeln("Post $uuid deleted");
        return Command::SUCCESS;
    }
}
