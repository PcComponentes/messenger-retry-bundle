<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle\Tests\Core;

use PcComponentes\MessengerRetryBundle\Core\MessengerRetryConfiguration;

final class MessengerRetryConfigurationGenerator
{
    public static function execute(
        int $maxRetries = 5,
        int $delayMilliseconds = 10000,
        float $multiplier = 6,
        int $maxDelayMilliseconds = 1800000,
        array $msgTypes = [],
    ): MessengerRetryConfiguration {
        return new MessengerRetryConfiguration(
            $maxRetries,
            $delayMilliseconds,
            $multiplier,
            $maxDelayMilliseconds,
            $msgTypes,
        );
    }
}
