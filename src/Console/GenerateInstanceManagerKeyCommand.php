<?php

namespace Flucava\RequestContextBundle\Console;

use Flucava\CqrsCore\Command\CommandBus;
use Flucava\RequestContext\Model\Command\GenerateInstanceManagerKey;
use Flucava\RequestContext\Model\Command\RemoveUri;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Philipp Marien
 */
class GenerateInstanceManagerKeyCommand extends Command
{
    public function __construct(private readonly CommandBus $commandBus)
    {
        parent::__construct('flucava:request-context:uri:remove');

        $this->addArgument('uri', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var \Flucava\RequestContext\Model\Result\InstanceManagerKey $key */
        $key = $this->commandBus->handle(
            new GenerateInstanceManagerKey(true)
        );

        $io = new SymfonyStyle($input, $output);
        $io->success('Successfully (re-)generated your instance manager key.');
        $io->newLine(2);
        $io->info('KEY: ' . $key->getKey());
        $io->newLine(2);
        $io->caution('Please store this key and keep it secret! If you loose this key, you have to generate a new one.');

        return self::SUCCESS;
    }
}
