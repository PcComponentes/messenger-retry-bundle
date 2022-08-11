<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle;

use PcComponentes\MessengerRetryBundle\DependencyInjection\MessengerRetryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class MessengerRetryBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(
            new MessengerRetryCompilerPass(),
        );
    }
}
