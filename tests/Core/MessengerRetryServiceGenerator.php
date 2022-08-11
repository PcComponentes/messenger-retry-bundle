<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle\Tests\Core;

use PcComponentes\MessengerRetryBundle\Core\MessengerRetryService;

final class MessengerRetryServiceGenerator
{
    public static function execute($retryConfigurations = null)
    {
        return new MessengerRetryService(
            \is_null($retryConfigurations)
                ? [MessengerRetryConfigurationGenerator::execute()]
                : $retryConfigurations,
        );
    }
}
