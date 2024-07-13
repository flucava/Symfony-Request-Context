<?php

namespace Flucava\RequestContextBundle\DependencyInjection;

use Flucava\CqrsCoreBundle\DependencyInjection\Compiler\CommandHandlerBusPass;
use Flucava\CqrsCoreBundle\DependencyInjection\Compiler\QueryHandlerBusPass;
use Flucava\RequestContext\CommandHandler\AddUriHandler;
use Flucava\RequestContext\CommandHandler\GenerateInstanceManagerKeyHandler;
use Flucava\RequestContext\CommandHandler\RegisterContextHandler;
use Flucava\RequestContext\CommandHandler\RemoveContextHandler;
use Flucava\RequestContext\CommandHandler\RemoveUriHandler;
use Flucava\RequestContext\CommandHandler\VerifyInstanceManagerKeyHandler;
use Flucava\RequestContext\QueryHandler\LoadContextByIdHandler;
use Flucava\RequestContext\QueryHandler\LoadContextByUriHandler;
use Flucava\RequestContext\Service\ContextProvider;
use Flucava\RequestContext\Service\FilenameProvider;
use Flucava\RequestContextBundle\Console\AddUriCommand;
use Flucava\RequestContextBundle\Console\GenerateInstanceManagerKeyCommand;
use Flucava\RequestContextBundle\Console\InitializeCommand;
use Flucava\RequestContextBundle\Console\RegisterContextCommand;
use Flucava\RequestContextBundle\Console\RemoveContextCommand;
use Flucava\RequestContextBundle\Console\RemoveUriCommand;
use Flucava\RequestContextBundle\Controller\ContextController;
use Flucava\RequestContextBundle\Controller\UriController;
use Flucava\RequestContextBundle\Listener\CommandListener;
use Flucava\RequestContextBundle\Listener\RequestListener;
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
            ->setPublic(false);

        $container->autowire(FilenameProvider::class)
            ->setPublic(false)
            ->setArgument('$storage', $mergedConfig['storage_path']);

        $container->autowire(LoadContextByIdHandler::class)
            ->setPublic(false)
            ->setArgument('$defaultSettings', $mergedConfig['default_settings'])
            ->addTag(QueryHandlerBusPass::SERVICE_TAG);

        $container->autowire(LoadContextByUriHandler::class)
            ->setPublic(false)
            ->setArgument('$mainUris', $mergedConfig['main_uris'])
            ->setArgument('$defaultSettings', $mergedConfig['default_settings'])
            ->addTag(QueryHandlerBusPass::SERVICE_TAG);

        $container->autowire(AddUriHandler::class)
            ->setPublic(false)
            ->addTag(CommandHandlerBusPass::SERVICE_TAG);

        $container->autowire(GenerateInstanceManagerKeyHandler::class)
            ->setPublic(false)
            ->addTag(CommandHandlerBusPass::SERVICE_TAG);

        $container->autowire(RegisterContextHandler::class)
            ->setPublic(false)
            ->addTag(CommandHandlerBusPass::SERVICE_TAG);

        $container->autowire(RemoveContextHandler::class)
            ->setPublic(false)
            ->addTag(CommandHandlerBusPass::SERVICE_TAG);

        $container->autowire(RemoveUriHandler::class)
            ->setPublic(false)
            ->addTag(CommandHandlerBusPass::SERVICE_TAG);

        $container->autowire(VerifyInstanceManagerKeyHandler::class)
            ->setPublic(false)
            ->addTag(CommandHandlerBusPass::SERVICE_TAG);

        $container->autowire(InitializeCommand::class)
            ->setArgument('$storage', $mergedConfig['storage_path'])
            ->addTag('console.command');

        $container->autowire(AddUriCommand::class)
            ->addTag('console.command');

        $container->autowire(GenerateInstanceManagerKeyCommand::class)
            ->addTag('console.command');

        $container->autowire(RegisterContextCommand::class)
            ->addTag('console.command');

        $container->autowire(RemoveContextCommand::class)
            ->addTag('console.command');

        $container->autowire(RemoveUriCommand::class)
            ->addTag('console.command');

        $container->autowire(CommandListener::class)
            ->addTag(
                'kernel.event_listener',
                ['event' => 'console.command']
            );

        $container->autowire(RequestListener::class)
            ->addTag(
                'kernel.event_listener',
                ['event' => 'kernel.request', 'priority' => 50]
            );

        $container->autowire(ContextController::class)
            ->addTag('controller.service_arguments');

        $container->autowire(UriController::class)
            ->addTag('controller.service_arguments');
    }
}
