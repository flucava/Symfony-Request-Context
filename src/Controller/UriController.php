<?php

namespace Flucava\RequestContextBundle\Controller;

use Flucava\RequestContext\Model\Command\AddUri;
use Flucava\RequestContext\Model\Command\RemoveUri;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

/**
 * @author Philipp Marien
 */
readonly class UriController extends AbstractController
{
    public function addUri(Request $request, string $uri): Response
    {
        try {
            $this->validate($request);

            $content = $this->getContent($request);
            if (!array_key_exists('context', $content) || !is_string($content['context'])) {
                throw new BadRequestHttpException('Missing "context" parameter');
            }

            $this->commandBus->handle(new AddUri($uri, $content['context']));

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->createJsonErrorResponse($exception);
        }
    }

    public function removeUri(Request $request, string $uri): Response
    {
        try {
            $this->validate($request);

            $this->commandBus->handle(new RemoveUri($uri));

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->createJsonErrorResponse($exception);
        }
    }
}
