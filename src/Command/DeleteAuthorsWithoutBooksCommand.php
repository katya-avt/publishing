<?php

namespace App\Command;

use App\Repository\AuthorRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'app:delete-authors-without-books')]
class DeleteAuthorsWithoutBooksCommand extends Command
{
    public AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->authorRepository->deleteAuthorsWithoutBooks();

        $output->write('Authors without books were deleted.' . PHP_EOL);

        return Command::SUCCESS;
    }
}
