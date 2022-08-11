<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle\Core;

use PcComponentes\Ddd\Util\Message\Message;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Retry\MultiplierRetryStrategy;

final class MessengerRetryConfiguration extends MultiplierRetryStrategy
{
    private array $msgTypes;

    public function __construct(
        int $maxRetries,
        int $delayMilliseconds,
        float $multiplier,
        int $maxDelayMilliseconds,
        array $msgTypes,
    ) {
        parent::__construct($maxRetries, $delayMilliseconds, $multiplier, $maxDelayMilliseconds);

        $this->msgTypes = $msgTypes;
    }

    public function isRetryable(Envelope $message, ?\Throwable $throwable = null): bool
    {
        $action = $this->getActionMessage(
            $this->messageFromEnvelope($message),
        );

        if (\is_bool($action)) {
            return $action;
        }

        return parent::isRetryable($message, $throwable);
    }

    public function isValidMessage(Envelope $message): bool
    {
        foreach (\array_keys($this->msgTypes) as $msgType) {
            if ($this->messageFromEnvelope($message) instanceof $msgType) {
                return true;
            }
        }

        return false;
    }

    private function getActionMessage(Message $message): ?bool
    {
        foreach ($this->msgTypes as $msgType => $action) {
            if ($message instanceof $msgType) {
                return $action;
            }
        }

        return null;
    }

    private function messageFromEnvelope(Envelope $message): Message
    {
        return $message->getMessage();
    }
}
