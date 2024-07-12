<?php

namespace Flucava\RequestContextBundle\Console;

use Flucava\CqrsCore\Command\CommandBus;
use Flucava\RequestContext\Model\Command\AddUri;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Philipp Marien
 */
class AddUriCommand extends Command
{
    public function __construct(private readonly CommandBus $commandBus)
    {
        parent::__construct('flucava:request-context:uri:add');

        $this->addArgument('uri', InputArgument::REQUIRED);
        $this->addArgument('context', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->handle(
            new AddUri(
                $input->getArgument('uri'),
                $input->getArgument('context')
            )
        );

        $io = new SymfonyStyle($input, $output);
        $io->success('Added URI: ' . $input->getArgument('uri'));

        return self::SUCCESS;
    }
}
