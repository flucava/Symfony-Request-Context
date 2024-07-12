<?php

namespace Flucava\RequestContextBundle\Listener;

use Flucava\RequestContext\Model\Exception\InvalidContextException;
use Flucava\RequestContext\Service\ContextProvider;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * @author Philipp Marien
 */
readonly class RequestListener
{
    public function __construct(private ContextProvider $contextProvider)
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        try {
            $this->contextProvider->loadContext(
                $event->getRequest()->getUri(),
                $event->getRequest()->headers->all()
            );
        } catch (InvalidContextException) {

        }
    }
}
