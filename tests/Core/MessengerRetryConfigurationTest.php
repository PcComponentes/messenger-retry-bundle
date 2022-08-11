<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle\Tests\Core;

use PcComponentes\Ddd\Application\Command;
use PcComponentes\MessengerRetryBundle\Core\MessengerRetryConfiguration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;

class MessengerRetryConfigurationTest extends TestCase
{
    /** @test */
    public function given_parameters_when_build_service_then_return_an_instance_of_the_service()
    {
        $service = MessengerRetryConfigurationGenerator::execute();
        self::assertInstanceOf(MessengerRetryConfiguration::class, $service);
    }

    /** @test */
    public function given_message_that_is_not_in_the_recognized_types_when_message_is_validated_then_it_returns_false()
    {
        $service = MessengerRetryConfigurationGenerator::execute(
            msgTypes: [
                \stdClass::class => null,
            ],
        );
        $messageMock = self::createMock(Command::class);
        $envelope = new Envelope($messageMock);

        self::assertEquals(false, $service->isValidMessage($envelope));
    }

    /** @test */
    public function given_message_that_is_not_in_the_recognized_types_when_queried_if_retryable_then_it_returns_true()
    {
        $service = MessengerRetryConfigurationGenerator::execute(
            msgTypes: [
                \stdClass::class => null,
            ],
        );
        $messageMock = self::createMock(Command::class);
        $envelope = new Envelope($messageMock);

        self::assertEquals(true, $service->isRetryable($envelope));
    }

    /**
     * @test
     * @dataProvider possibleCases
     */
    public function given_a_new_message_when_the_class_has_auto_retry_then_retry_with_given_time(
        ?bool $retryPolicy,
        int $retryCount,
        bool $isRetryable,
        int $waitingTime,
    ) {
        $service = MessengerRetryConfigurationGenerator::execute(
            msgTypes: [
                Command::class => $retryPolicy,
            ],
        );

        $messageMock = self::createMock(Command::class);

        $envelope = new Envelope(
            $messageMock,
            [RedeliveryStampGenerator::execute($retryCount)],
        );

        self::assertEquals(true, $service->isValidMessage($envelope));
        self::assertEquals($isRetryable, $service->isRetryable($envelope));
        self::assertEquals($waitingTime, $service->getWaitingTime($envelope));
    }

    public function possibleCases(): array
    {
        return [
            [true, 0, true, 10000],
            [true, 1, true, 60000],
            [true, 2, true, 360000],
            [true, 3, true, 1800000],
            [true, 5, true, 1800000],
            [false, 0, false, 10000],
            [null, 0, true, 10000],
            [null, 1, true, 60000],
            [null, 2, true, 360000],
            [null, 3, true, 1800000],
            [null, 5, false, 1800000],
        ];
    }
}
