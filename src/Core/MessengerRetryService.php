<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle\Core;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Retry\RetryStrategyInterface;

final class MessengerRetryService implements RetryStrategyInterface
{
    private array $retryConfigurations;

    public function __construct(array $retryConfigurations)
    {
        $this->retryConfigurations = $retryConfigurations;
    }

    public function isRetryable(Envelope $message, \Throwable $throwable = null): bool
    {
        $retryConfiguration = $this->getRetryConfiguration($message);

        if (null === $retryConfiguration) {
            return false;
        }

        return $retryConfiguration->isRetryable($message);
    }

    public function getWaitingTime(Envelope $message, \Throwable $throwable = null): int
    {
        $retryConfiguration = $this->getRetryConfiguration($message);

        if (null === $retryConfiguration) {
            return 0;
        }

        return $retryConfiguration->getWaitingTime($message);
    }

    private function getRetryConfiguration(Envelope $message): ?MessengerRetryConfiguration
    {
        $retryConfigurations = \array_filter(
            $this->retryConfigurations,
            static fn (MessengerRetryConfiguration $rc) => $rc->isValidMessage($message),
        );

        if (0 === \count($retryConfigurations)) {
            return null;
        }

        return \current($retryConfigurations);
    }
}
