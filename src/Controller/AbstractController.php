<?php

namespace Flucava\RequestContextBundle\Controller;

use Flucava\CqrsCore\Command\CommandBus;
use Flucava\RequestContext\Model\Command\VerifyInstanceManagerKey;
use Flucava\RequestContext\Model\Exception\InvalidInstanceManagerKeyException;
use Flucava\RequestContext\Service\ContextProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

/**
 * @author Philipp Marien
 */
abstract readonly class AbstractController
{
    public function __construct(protected ContextProvider $contextProvider, protected CommandBus $commandBus)
    {
    }

    /**
     * @throws \Throwable
     */
    protected function validate(Request $request): void
    {
        if (!$this->contextProvider->getContext()->isMainContext()) {
            throw new NotFoundHttpException('Not found');
        }

        $authorization = $request->headers->get('Authorization');
        if (!str_starts_with($authorization, 'Bearer ')) {
            throw new UnauthorizedHttpException('Missing bearer token');
        }

        try {
            $this->commandBus->handle(new VerifyInstanceManagerKey(substr($authorization, 7)));
        } catch (InvalidInstanceManagerKeyException) {
            throw new AccessDeniedHttpException('Invalid instance manager key');
        }
    }

    /**
     * @throws \Throwable
     */
    protected function getContent(Request $request): array
    {
        if (!json_validate($request->getContent())) {
            throw new BadRequestHttpException('Invalid json provided');
        }

        return json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    protected function createJsonErrorResponse(Throwable $exception): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ],
            ($exception instanceof HttpException ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR)
        );
    }
}
