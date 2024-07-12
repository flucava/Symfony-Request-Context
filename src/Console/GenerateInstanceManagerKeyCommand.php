<?php

namespace Flucava\RequestContextBundle\Console;

use Flucava\CqrsCore\Command\CommandBus;
use Flucava\RequestContext\Model\Command\GenerateInstanceManagerKey;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Philipp Marien
 */
class GenerateInstanceManagerKeyCommand extends Command
{
    public function __construct(private readonly CommandBus $commandBus)
    {
        parent::__construct('flucava:request-context:instance-manager:generate-key');

        $this->addOption('force', 'f', InputOption::VALUE_NONE);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var \Flucava\RequestContext\Model\Result\InstanceManagerKey $key */
        $key = $this->commandBus->handle(
            new GenerateInstanceManagerKey(true)
        );

        if (!$input->getOption('force')) {
            throw new InvalidArgumentException('Please execute this command with the option --force');
        }

        $io = new SymfonyStyle($input, $output);
        $io->success('Successfully (re-)generated your instance manager key.');
        $io->newLine();
        $io->comment('KEY: ' . $key->getKey());
        $io->newLine();
        $io->caution('Please store this key at your instance manager and keep it secret! If you loose this key, you have to generate a new one.');

        return self::SUCCESS;
    }
}
