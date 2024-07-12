<?php

namespace Flucava\RequestContextBundle\Console;

use Flucava\CqrsCore\Command\CommandBus;
use Flucava\RequestContext\Model\Command\RegisterContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Philipp Marien
 */
class RegisterContextCommand extends Command
{
    public function __construct(private readonly CommandBus $commandBus)
    {
        parent::__construct('flucava:request-context:context:register');

        $this->addArgument('uuid', InputArgument::REQUIRED);
        $this->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->handle(
            new RegisterContext(
                $input->getArgument('uuid'),
                $input->getArgument('name'),
                []
            )
        );

        $io = new SymfonyStyle($input, $output);
        $io->success('Registered context "' . $input->getArgument('name') . '"');

        return self::SUCCESS;
    }
}
