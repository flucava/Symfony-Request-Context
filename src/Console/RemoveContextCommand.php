<?php

namespace Flucava\RequestContextBundle\Console;

use Flucava\CqrsCore\Command\CommandBus;
use Flucava\RequestContext\Model\Command\RemoveContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Philipp Marien
 */
class RemoveContextCommand extends Command
{
    public function __construct(private readonly CommandBus $commandBus)
    {
        parent::__construct('flucava:request-context:context:remove');

        $this->addArgument('uuid', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->handle(
            new RemoveContext(
                $input->getArgument('uuid')
            )
        );

        $io = new SymfonyStyle($input, $output);
        $io->success('Removed context "' . $input->getArgument('uuid') . '"');

        return self::SUCCESS;
    }
}
