<?php

namespace Flucava\RequestContextBundle\Console;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Philipp Marien
 */
class InitializeCommand extends Command
{
    public function __construct(private readonly string $storage)
    {
        parent::__construct('flucava:request-context:initialize');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!is_dir($this->storage) && !mkdir($directory = $this->storage) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        return self::SUCCESS;
    }
}

