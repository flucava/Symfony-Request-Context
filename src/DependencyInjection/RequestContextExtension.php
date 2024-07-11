<?php

namespace Flucava\RequestContextBundle\DependencyInjection;

use Flucava\RequestContext\CommandHandler\AddUriHandler;
use Flucava\RequestContext\CommandHandler\RegisterContextHandler;
use Flucava\RequestContext\CommandHandler\RemoveContextHandler;
use Flucava\RequestContext\CommandHandler\RemoveUriHandler;
use Flucava\RequestContext\QueryHandler\LoadContextByIdHandler;
use Flucava\RequestContext\QueryHandler\LoadContextByUriHandler;
use Flucava\RequestContext\Service\ContextProvider;
use Flucava\RequestContext\Service\FilenameProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @author Philipp Marien
 */
class RequestContextExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->autowire(ContextProvider::class)
            ->setPublic(false)
            ->setArgument('$defaultSettings', $mergedConfig['default_settings']);

        $container->autowire(FilenameProvider::class)
            ->setPublic(false)
            ->setArgument('$storage', $mergedConfig['storage_path']);

        $container->autowire(LoadContextByIdHandler::class)
            ->setPublic(false);

        $container->autowire(LoadContextByUriHandler::class)
            ->setPublic(false);

        $container->autowire(AddUriHandler::class)
            ->setPublic(false);

        $container->autowire(RegisterContextHandler::class)
            ->setPublic(false);

        $container->autowire(RemoveContextHandler::class)
            ->setPublic(false);

        $container->autowire(RemoveUriHandler::class)
            ->setPublic(false);
    }
}
