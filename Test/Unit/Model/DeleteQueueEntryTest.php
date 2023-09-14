<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\DeleteQueueEntry;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterfaceFactory;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteQueueEntryTest extends TestCase
{
    private DeleteQueueEntry $deleteQueueEntry;
    private AiContentQueueEntryInterfaceFactory|MockObject $aiContentQueueEntryFactoryMock;
    private AiContentQueueEntry|MockObject $aiContentQueueEntryResourceMock;

    protected function setUp(): void
    {
        $this->aiContentQueueEntryFactoryMock = $this->createMock(AiContentQueueEntryInterfaceFactory::class);
        $this->aiContentQueueEntryResourceMock = $this->createMock(AiContentQueueEntry::class);

        $this->deleteQueueEntry = new DeleteQueueEntry(
            $this->aiContentQueueEntryResourceMock,
            $this->aiContentQueueEntryFactoryMock
        );
    }

    public function testExecute(): void
    {
        $entryId = 123;

        $mockEntry = $this->createMock(\Creatuity\AIContentMassAction\Model\AiContentQueueEntry::class);

        $this->aiContentQueueEntryFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($mockEntry);

        $this->aiContentQueueEntryResourceMock->expects($this->once())
            ->method('load')
            ->with($mockEntry, $entryId)
            ->willReturn($mockEntry);

        $this->aiContentQueueEntryResourceMock->expects($this->once())
            ->method('delete')
            ->with($mockEntry);

        $this->deleteQueueEntry->execute($entryId);
    }
}
