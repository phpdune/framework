<?php

declare(strict_types=1);

namespace Dune\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClearViewCache extends Command
{
    /**
     * \Symfony\Component\Console\Style\SymfonyStyle instance
     *
     * @var SymfonyStyle
     */
    protected SymfonyStyle $msg;
    /**
     * command name
     *
     * @var string
     */
    protected static $defaultName = 'view:clear';

    /**
     * default symfony console configure method
     * setting description and arguments
     *
     */
    protected function configure(): void
    {
        $this
        ->setDescription('Clear the view cache');
    }
    /**
     * clear view cache
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->msg = new SymfonyStyle($input, $output);
        $files = glob(config('pine.cache_path').'/*.php');
        if(empty($files)) {
            $this->msg->error('Nothing to clear');
            return Command::FAILURE;
        }
        foreach($files as $file) {
            if($file != '.gitignore') {
                unlink($file);
            }
        }
        $this->msg->success('View cache cleared successfully');
        return Command::SUCCESS;
    }
}
