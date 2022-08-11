<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle\Tests\Core;

use PcComponentes\Ddd\Application\Command;
use PcComponentes\MessengerRetryBundle\Core\MessengerRetryConfiguration;
use PcComponentes\MessengerRetryBundle\Core\MessengerRetryService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;

class MessengerRetryServiceTest extends TestCase
{
    private MockObject $configurationMock;
    private MessengerRetryService $messengerRetryService;

    /** @test */
    public function given_parameters_when_build_service_then_return_an_instance_of_the_service()
    {
        $service = MessengerRetryServiceGenerator::execute();

        self::assertInstanceOf(MessengerRetryService::class, $service);
    }

    /** @test */
    public function given_an_invalid_message_when_checked_for_retryable_then_returns_false()
    {
        $this->configurationMock
            ->expects(self::once())
            ->method('isValidMessage')
            ->willReturn(false);

        $envelope = new Envelope(
            self::createMock(Command::class),
        );

        self::assertEquals(false, $this->messengerRetryService->isRetryable($envelope));
    }

    /** @test */
    public function given_an_valid_message_when_checked_for_retryable_then_config_method_is_called_and_returns_true()
    {
        $this->configurationMock
            ->expects(self::once())
            ->method('isValidMessage')
            ->willReturn(true);

        $this->configurationMock
            ->expects(self::once())
            ->method('isRetryable')
            ->willReturn(true);

        $envelope = new Envelope(
            self::createMock(Command::class),
        );

        self::assertEquals(true, $this->messengerRetryService->isRetryable($envelope));
    }

    /** @test */
    public function given_an_valid_message_when_get_waiting_time_is_called_then_returns_zero()
    {
        $this->configurationMock
            ->expects(self::once())
            ->method('isValidMessage')
            ->willReturn(false);

        $envelope = new Envelope(
            self::createMock(Command::class),
        );

        self::assertEquals(0, $this->messengerRetryService->getWaitingTime($envelope));
    }

    /** @test */
    public function given_an_valid_message_when_get_waiting_time_is_called_then_calls_back_the_configurator()
    {
        $waitingTime = \rand(1, 9999);

        $this->configurationMock
            ->expects(self::once())
            ->method('isValidMessage')
            ->willReturn(true);

        $this->configurationMock
            ->expects(self::once())
            ->method('getWaitingTime')
            ->willReturn($waitingTime);

        $envelope = new Envelope(
            self::createMock(Command::class),
        );

        self::assertEquals($waitingTime, $this->messengerRetryService->getWaitingTime($envelope));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->configurationMock = self::createMock(MessengerRetryConfiguration::class);

        $this->messengerRetryService = MessengerRetryServiceGenerator::execute([$this->configurationMock]);
    }
}
