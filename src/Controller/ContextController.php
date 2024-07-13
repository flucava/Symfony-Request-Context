<?php

namespace Flucava\RequestContextBundle\Controller;

use Flucava\RequestContext\Model\Command\RegisterContext;
use Flucava\RequestContext\Model\Command\RemoveContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

/**
 * @author Philipp Marien
 */
readonly class ContextController extends AbstractController
{
    public function registerContext(Request $request, string $uuid): Response
    {
        try {
            $this->validate($request);

            $content = $this->getContent($request);
            if (!array_key_exists('name', $content) || !is_string($content['name'])) {
                throw new BadRequestHttpException('Missing "name" parameter');
            }
            if (!array_key_exists('settings', $content) || !is_array($content['settings'])) {
                throw new BadRequestHttpException('Missing "settings" parameter');
            }

            $this->commandBus->handle(new RegisterContext($uuid, $content['name'], $content['settings']));

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->createJsonErrorResponse($exception);
        }
    }

    public function removeContext(Request $request, string $uuid): Response
    {
        try {
            $this->validate($request);

            $this->commandBus->handle(new RemoveContext($uuid));

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->createJsonErrorResponse($exception);
        }
    }
}
