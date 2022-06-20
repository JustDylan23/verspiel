<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:cache:clear',
    description: 'Clear opcache',
)]
class ClearCacheCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        opcache_reset();
        return Command::SUCCESS;
    }
}
