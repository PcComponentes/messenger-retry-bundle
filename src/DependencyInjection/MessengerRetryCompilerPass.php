<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle\DependencyInjection;

use PcComponentes\MessengerRetryBundle\Core\MessengerRetryService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class MessengerRetryCompilerPass implements CompilerPassInterface
{
    public const TAG_NAME = 'pccomponentes.messenger_retry.configuration';

    public function process(ContainerBuilder $container): void
    {
        $configurations = \array_keys(
            $container->findTaggedServiceIds(self::TAG_NAME),
        );

        $references = [];

        foreach ($configurations as $configuration) {
            $references[] = new Reference($configuration);
        }

        $definition = new Definition(
            MessengerRetryService::class,
            [$references],
        );

        $container->addDefinitions([
            MessengerRetryService::class => $definition,
        ]);
    }
}
