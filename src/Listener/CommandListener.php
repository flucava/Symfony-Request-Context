<?php

namespace Flucava\RequestContextBundle\Listener;

use Flucava\RequestContext\Model\View\Context;
use Flucava\RequestContext\Service\ContextProvider;
use Flucava\RequestContextBundle\Console\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

/**
 * @author Philipp Marien
 */
readonly class CommandListener
{
    public function __construct(private ContextProvider $contextProvider)
    {
    }

    public function __invoke(ConsoleCommandEvent $event): void
    {
        if (!$event->getCommand() instanceof Command) {
            return;
        }

        $context = $event->getInput()->getOption('context');
        if (!$context) {
            $context = Context::MAIN_ID;
        }

        $this->contextProvider->loadContext(
            'WITHOUT_URI',
            [ContextProvider::CONTEXT_HEADER => $context]
        );
    }
}
