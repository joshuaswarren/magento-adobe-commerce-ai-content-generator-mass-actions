<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\AiContentQueueEntryHydrator;
use Creatuity\AIContentMassAction\Model\CreateAiContentQueueEntry;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterfaceFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateAiContentQueueEntryTest extends TestCase
{
    private readonly CreateAiContentQueueEntry $createAiContentQueueEntry;
    private readonly AiContentQueueEntryInterfaceFactory|MockObject $aiContentQueueEntryFactoryMock;
    private readonly AiContentQueueEntryHydrator|MockObject $aiContentQueueEntryHydratorMock;

    protected function setUp(): void
    {
        $this->aiContentQueueEntryFactoryMock = $this->createMock(AiContentQueueEntryInterfaceFactory::class);
        $this->aiContentQueueEntryHydratorMock = $this->createMock(AiContentQueueEntryHydrator::class);

        $this->createAiContentQueueEntry = new CreateAiContentQueueEntry(
            $this->aiContentQueueEntryFactoryMock,
            $this->aiContentQueueEntryHydratorMock
        );
    }

    public function testCreate(): void
    {
        $data = ['some_key' => 'some_value'];

        $mockEntry = $this->createMock(AiContentQueueEntryInterface::class);

        $this->aiContentQueueEntryFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($mockEntry);

        $this->aiContentQueueEntryHydratorMock->expects($this->once())
            ->method('hydrate')
            ->with($mockEntry, $data)
            ->willReturn($mockEntry);

        $result = $this->createAiContentQueueEntry->create($data);

        $this->assertSame($mockEntry, $result);
    }
}
