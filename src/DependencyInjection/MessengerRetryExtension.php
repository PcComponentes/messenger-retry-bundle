<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle\DependencyInjection;

use PcComponentes\Ddd\Util\Message\Message;
use PcComponentes\MessengerRetryBundle\Core\MessengerRetryConfiguration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class MessengerRetryExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as $key => $value) {
            $msgTypes = [];

            foreach ($value['msg_types'] as $msgType) {
                foreach ($msgType as $class => $action) {
                    $this->isExtendsMessage($class);

                    $resultAction = null;

                    if (\in_array($action, ['always_retry', 'never_retry'], true)) {
                        $resultAction = 'always_retry' === $action;
                    }

                    $msgTypes[$class] = $resultAction;
                }
            }

            $definition = new Definition(
                MessengerRetryConfiguration::class,
                [
                    $value['max_retries'],
                    $value['delay_milliseconds'],
                    $value['multiplier'],
                    $value['max_delay_milliseconds'],
                    $msgTypes,
                ],
            );

            $definition = $definition->addTag(MessengerRetryCompilerPass::TAG_NAME);

            $container->addDefinitions([
                $this->aliasFromKey($key) => $definition,
            ]);
        }
    }

    private function aliasFromKey(int $key): string
    {
        return MessengerRetryCompilerPass::TAG_NAME . '.' . $key;
    }

    private function isExtendsMessage(string $class): void
    {
        $reflectionClass = new \ReflectionClass($class);

        if ($reflectionClass->isSubclassOf(Message::class)) {
            return;
        }

        throw new \Exception(
            \sprintf(
                'Class "%s" was expected to be subclass of "%s".',
                $class,
                Message::class,
            ),
        );
    }
}
