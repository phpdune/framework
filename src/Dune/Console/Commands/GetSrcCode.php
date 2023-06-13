<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Dune\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GetSrcCode extends Command
{

    /**
     * command name
     *
     * @var string
     */
    protected static $defaultName = "src";

    /**
     * default symfony console configure method
     * setting description and arguments
     *
     */
    protected function configure(): void
    {
        $this->setDescription('Framework source code');
    }
    /**
     * clear view cache
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $srcCode = "https://github.com/phpdune/framework";
        $output->writeln("Opening {$srcCode}");
        $this->openUrl($srcCode);
        return Command::SUCCESS;
    }
    /**
     * open the development server in the browser
     *
     * @param string $host
     * @param string $port
     *
     */
    private function openUrl(string $url): void
    {
        $os = strtolower(PHP_OS);
        $cmd = "";
        if (strpos($os, "darwin")) {
            $cmd = "open";
        } elseif (strpos($os, "win")) {
            $cmd = "start";
        } else {
            $cmd = "xdg-open";
        }
        if (!empty($cmd)) {
            $fullCmd = $cmd .' '. $url;
            shell_exec($fullCmd);
        }
    }
}
