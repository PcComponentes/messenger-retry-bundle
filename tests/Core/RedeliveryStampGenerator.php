<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle\Tests\Core;

use Symfony\Component\Messenger\Stamp\RedeliveryStamp;

final class RedeliveryStampGenerator
{
    public static function execute(int $retryCount = 0): RedeliveryStamp
    {
        return new RedeliveryStamp($retryCount);
    }
}
